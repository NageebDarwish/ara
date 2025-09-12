<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\{User, VideoTimeline, VideoSeriesTimeline, WatchedVideo};
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use App\Services\BadgeAssignmentService;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Subscription;
use App\Helpers\UploadFiles;
use Illuminate\Support\Facades\DB;
use App\Models\Goal;

class AuthController extends Controller
{
    use ResponseTrait;
    protected $badgeService;

    public function __construct(BadgeAssignmentService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function login(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if (!$user->is_verified) {
                    return $this->sendError('Please verify your email to login.');
                }
                if($user->is_premium == 1)
                {
                    $this->badgeService->assignSpecialAchievementBadge('Eternal Light');
                }

                $success['token'] = $user->createToken('API TOKEN')->plainTextToken;
                $success['user'] = $user;

                return $this->sendResponse($success, 'Login success');
            } else {
                return $this->sendError('Invalid credentials');
            }
        });
    }


public function register(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
            ]);



            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }
            $user=User::where('email',$request->email)->first();
            if($user)
            {
                $token = $user->createToken('API TOKEN')->plainTextToken;
                return response()->json([
                    'data'=>$user,
                    'exists'=>true,
                    'token'=>$token,
                    'message'=>'User Existes',
                    ]);
            }

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['role'] = 'user';
            $otp = rand(1000, 9999);
            $input['otp'] = $otp;
            $input['progress_level_id'] = 1;
            $user = User::create($input);
             $goal=Goal::create([
                'user_id' => $user->id,
                'date' => now(),
                'target_minutes' => 15,
            ]);
            $token = $user->createToken('API TOKEN')->plainTextToken;
            $plan=Plan::where('is_default',1)->first();
            Subscription::create([
                'user_id'=>$user->id,
                'plan_id'=>$plan->id,
                'amount'=>'0',
                'start_date'=>now(),
                'end_date' => now()->addYears(10),
                'status'=>'active',
            ]);
            Mail::to($user->email)->send(new SendOtpMail($otp));

           return response()->json([
                     'data'=>$user,
                     'exists'=>false,
                     'token'=>$token,
                     'message'=>'Otp sent to your email',
                     ]);
        });
    }
   

       public function verifyOtp(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|digits:4',
                'type'=>'sometimes',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            $user = User::where('email', $request->email)->first();

            if ($user->otp === $request->otp) {
                if($request->type==='forgot_password')
                {
                    $verificationToken = bin2hex(random_bytes(16));
                    $expiryTime = now()->addMinutes(30);
                    $user->update([
                        'otp' => null,
                        'verification_token' => $verificationToken,
                        'verification_token_expires_at' => $expiryTime,
                    ]);
                    return $this->sendResponse($verificationToken, 'Otp verification successfully');
                }
                $user->update([
                    'otp' => null,
                    'is_verified' => true,
                ]);

                $success['token'] = $user->createToken('API TOKEN')->plainTextToken;
                $success['user'] = $user;

                return $this->sendResponse($success, 'User verified successfully');
            } else {
                return $this->sendError('Invalid  OTP');
            }
        });
    }


    public function resendOtp(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            $user = User::where('email', $request->email)->first();

            if (!$user->is_verified) {
                $otp = rand(1000, 9999);
                $user->update(['otp' => $otp]);
                Mail::to($user->email)->send(new SendOtpMail($otp));

                return $this->sendResponse([], 'A new OTP has been sent to your email.');
            } else {
                return $this->sendError('This account is already verified.');
            }
        });
    }

    public function uderDetail()
    {
        return ExceptionHandlerHelper::tryCatch(function()  {
            $user = auth()->user();
            return $this->sendResponse($user, 'Login User Detail');
        });
    }

  
public function dashboardStatics()
{
    return ExceptionHandlerHelper::tryCatch(function () {
        $user = auth()->user();
        $now = Carbon::now();
        
        // ===== Weekly Breakdown (Grouped by Day of Week) =====
        $weeklyBreakdown = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Initialize with 0 values for each day
        foreach ($daysOfWeek as $day) {
            $weeklyBreakdown[$day] = [
                'total_watch_time' => 0,
                'video_watch_time' => 0,
                'series_watch_time' => 0
            ];
        }

        // Get all timeline updates from the last 7 days
        $videoTimeUpdates = DB::table('video_timelines')
            ->where('user_id', $user->id)
            ->where('updated_at', '>=', $now->copy()->subDays(7))
            ->select([
                DB::raw('DAYNAME(updated_at) as day'),
                'watched_time'
            ])
            ->get();

        $seriesTimeUpdates = DB::table('video_series_timelines')
            ->where('user_id', $user->id)
            ->where('updated_at', '>=', $now->copy()->subDays(7))
            ->select([
                DB::raw('DAYNAME(updated_at) as day'),
                'watched_time'
            ])
            ->get();

        // Process video updates
        foreach ($videoTimeUpdates as $update) {
            $day = $update->day;
            if (isset($weeklyBreakdown[$day])) {
                $weeklyBreakdown[$day]['video_watch_time'] += $update->watched_time;
                $weeklyBreakdown[$day]['total_watch_time'] += $update->watched_time;
            }
        }

        // Process series updates
        foreach ($seriesTimeUpdates as $update) {
            $day = $update->day;
            if (isset($weeklyBreakdown[$day])) {
                $weeklyBreakdown[$day]['series_watch_time'] += $update->watched_time;
                $weeklyBreakdown[$day]['total_watch_time'] += $update->watched_time;
            }
        }

        // ===== Monthly Breakdown (Grouped by Month) =====
        $monthlyBreakdown = [];
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Initialize with 0 values for each month
        foreach ($months as $month) {
            $monthlyBreakdown[$month] = [
                'total_watch_time' => 0,
                'video_watch_time' => 0,
                'series_watch_time' => 0
            ];
        }

        // Get all timeline updates from current year
        $yearlyVideoUpdates = DB::table('video_timelines')
            ->where('user_id', $user->id)
            ->whereYear('updated_at', $now->year)
            ->select([
                DB::raw('MONTHNAME(updated_at) as month'),
                'watched_time'
            ])
            ->get();

        $yearlySeriesUpdates = DB::table('video_series_timelines')
            ->where('user_id', $user->id)
            ->whereYear('updated_at', $now->year)
            ->select([
                DB::raw('MONTHNAME(updated_at) as month'),
                'watched_time'
            ])
            ->get();

        // Process video updates
        foreach ($yearlyVideoUpdates as $update) {
            $month = $update->month;
            if (isset($monthlyBreakdown[$month])) {
                $monthlyBreakdown[$month]['video_watch_time'] += $update->watched_time;
                $monthlyBreakdown[$month]['total_watch_time'] += $update->watched_time;
            }
        }

        // Process series updates
        foreach ($yearlySeriesUpdates as $update) {
            $month = $update->month;
            if (isset($monthlyBreakdown[$month])) {
                $monthlyBreakdown[$month]['series_watch_time'] += $update->watched_time;
                $monthlyBreakdown[$month]['total_watch_time'] += $update->watched_time;
            }
        }

        // ===== Totals =====
        $weeklyTotal = array_sum(array_column($weeklyBreakdown, 'total_watch_time'));
        $monthlyTotal = array_sum(array_column($monthlyBreakdown, 'total_watch_time'));

        $watchedVideo = WatchedVideo::where('user_id', $user->id)->first();
        $totalWatchedVideoCount = $watchedVideo->video_count ?? 0;

        $data = [
            // Totals
            'weekly_total' => $weeklyTotal,
            'monthly_total' => $monthlyTotal,
            'total_watched_video_count' => $totalWatchedVideoCount,
            
            // Breakdowns for charts
            'weekly_breakdown' => $weeklyBreakdown,
            'monthly_breakdown' => $monthlyBreakdown,
        ];

        return $this->sendResponse($data, 'Dashboard Statistics with Accurate Watch Time Tracking');
    });
}


    public function editProfile(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function()use($request){
            $data=$request->validate([
                'name'=>'nullable|string|max:255',
                'profile_image'=>'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);
            $user=auth()->user();
            if(isset($data['profile_image']))
            {
                if($user->profile_image)
                {
                    UploadFiles::delete($user->profile_image,'profileImages');
                }
                $data['profile_image']=UploadFiles::upload($data['profile_image'],'profileImages');
            }
            $user->update($data);
            return $this->sendResponse($user,'Profile updated successfully');
        });
    }

    public function changePassword(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$request->validate([
                'old_password'=>'required',
                'new_password'=>'required|confirmed',
            ]);
            $user=auth()->user();
            if(!Hash::check($data['old_password'],$user->password))
            {
                return $this->sendError('Old password is incorrect');
            }
            $user->update([
                'password'=>Hash::make($data['new_password']),
            ]);
            return $this->sendResponse($user,'password updated successfully');
        });
    }
    
     public function forgotPassword(Request $request)
    {
        
        // return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $request->validate([
                'email' => 'required|exists:users,email',
            ]);
           $user = User::where('email', $request->email)->first();
           
            $otp = rand(1000, 9999);
            $user->update(['otp' => $otp]);
            Mail::to($user->email)->send(new SendOtpMail($otp));
            return $this->sendResponse('', 'OTP sent to your email.');
        // });
    }

    public function newPassword(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $request->validate([
                'email' => 'required|exists:users,email',
                'password' => 'required|confirmed',
                'verification_token' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || $user->verification_token !== $request->verification_token) {
                return $this->sendError('','Invalid verification token');
            }

            if (now()->greaterThan($user->verification_token_expires_at)) {
                return $this->sendError('','Verification token has expired.');
            }

            $user->update([
                'password' => Hash::make($request->password),
                'verification_token' => null,
                'verification_token_expires_at' => null,
            ]);

            $success['token'] = $user->createToken('API TOKEN')->plainTextToken;
            $success['user'] = $user;
            return $this->sendResponse($success, 'Password changed successfully');
        });
    }
}