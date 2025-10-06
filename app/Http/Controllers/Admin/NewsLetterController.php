<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewsLetter as NewsLetterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\{User, Newsletter};
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class NewsLetterController extends Controller
{

    public function index()
    {
        return view('admin.modules.newsletter.index');
    }

    public function getNewslettersData(Request $request)
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc');

        return DataTables::of($newsletters)
            ->addIndexColumn()
            ->addColumn('recipient_info', function ($newsletter) {
                $types = [
                    'all' => '<span class="badge badge-dark">All Users</span>',
                    'premium' => '<span class="badge badge-warning">Premium Only</span>',
                    'free' => '<span class="badge badge-info">Free Only</span>',
                    'selected' => '<span class="badge badge-primary">Selected (' . count($newsletter->selected_users ?? []) . ')</span>',
                ];
                return $types[$newsletter->recipient_type] ?? '<span class="badge badge-secondary">' . ucfirst($newsletter->recipient_type) . '</span>';
            })
            ->addColumn('status_badge', function ($newsletter) {
                $badges = [
                    'draft' => '<span class="badge badge-secondary">Draft</span>',
                    'scheduled' => '<span class="badge badge-warning">Scheduled</span>',
                    'sent' => '<span class="badge badge-success">Sent</span>',
                ];
                return $badges[$newsletter->status] ?? '<span class="badge badge-secondary">' . ucfirst($newsletter->status) . '</span>';
            })
            ->addColumn('scheduled_date', function ($newsletter) {
                if ($newsletter->scheduled_at) {
                    return '<span class="local-time" data-utc="' . $newsletter->scheduled_at->toIso8601String() . '">' . $newsletter->scheduled_at->format('d M Y H:i') . ' UTC</span>';
                }
                return 'N/A';
            })
            ->addColumn('sent_date', function ($newsletter) {
                if ($newsletter->sent_at) {
                    return '<span class="local-time" data-utc="' . $newsletter->sent_at->toIso8601String() . '">' . $newsletter->sent_at->format('d M Y H:i') . ' UTC</span>';
                }
                return 'N/A';
            })
            ->addColumn('actions', function ($newsletter) {
                $actions = '<div class="d-flex align-items-center" style="gap: 0.5rem;">';
                if ($newsletter->status !== 'sent') {
                    $actions .= '<a href="' . route('admin.newsletter.edit', $newsletter->id) . '" class="btn btn-warning btn-sm" title="Edit Newsletter"><i class="fa fa-edit"></i></a>';
                }
                $actions .= '<form action="' . route('admin.newsletter.destroy', $newsletter->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Delete Newsletter"><i class="fa fa-trash"></i></button>
                </form>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['recipient_info', 'status_badge', 'scheduled_date', 'sent_date', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.modules.newsletter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required',
            'status' => 'required|in:draft,scheduled,send_now',
            'scheduled_at' => 'nullable|date',
            'recipient_type' => 'required|in:all,premium,free,selected',
            'selected_users' => 'nullable|array',
            'selected_users.*' => 'exists:users,id',
        ]);

        $data = [
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => $request->status === 'send_now' ? 'sent' : $request->status,
            'recipient_type' => $request->recipient_type,
            'selected_users' => $request->recipient_type === 'selected' ? $request->selected_users : null,
        ];

        // Handle scheduling (just save, don't send yet)
        if ($request->status === 'scheduled' && !empty($request->scheduled_at)) {
            $data['scheduled_at'] = Carbon::parse($request->scheduled_at, $request->input('timezone', 'UTC'))->utc();

            // Calculate recipients count for scheduled newsletter
            $users = $this->getRecipients($request->recipient_type, $request->selected_users);
            $data['recipients_count'] = $users->count();

            Newsletter::create($data);
            return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter scheduled successfully. It will be sent automatically at the scheduled time.');
        }

        // Handle draft (just save)
        if ($request->status === 'draft') {
            // Calculate recipients count for draft newsletter
            $users = $this->getRecipients($request->recipient_type, $request->selected_users);
            $data['recipients_count'] = $users->count();

            Newsletter::create($data);
            return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter saved as draft.');
        }

        // Handle immediate sending
        if ($request->status === 'send_now') {
            $users = $this->getRecipients($request->recipient_type, $request->selected_users);
            $count = 0;

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new NewsLetterMail($request->subject, $request->body));
                    $count++;
                } catch (\Exception $e) {
                    // Log error but continue
                    Log::error("Newsletter send failed to {$user->email}: " . $e->getMessage());
                }
            }

            $data['sent_at'] = now();
            $data['recipients_count'] = $count;
            Newsletter::create($data);
            return redirect()->route('admin.newsletter.index')->with('success', "Newsletter sent successfully to {$count} recipients.");
        }

        // Calculate recipients count for default case
        $users = $this->getRecipients($request->recipient_type, $request->selected_users);
        $data['recipients_count'] = $users->count();

        Newsletter::create($data);
        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter created successfully.');
    }

    private function getRecipients($recipientType, $selectedUsers = null)
    {
        $query = User::where('role', 'user');

        switch ($recipientType) {
            case 'premium':
                $query->where('is_premium', 1);
                break;
            case 'free':
                $query->where('is_premium', 0);
                break;
            case 'selected':
                if ($selectedUsers) {
                    $query->whereIn('id', $selectedUsers);
                }
                break;
            case 'all':
            default:
                // No additional filter
                break;
        }

        return $query->get();
    }

    public function edit($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletter.index')->with('error', 'Cannot edit sent newsletters.');
        }

        return view('admin.modules.newsletter.edit', compact('newsletter'));
    }

    public function update(Request $request, $id)
    {
        $newsletter = Newsletter::findOrFail($id);

        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletter.index')->with('error', 'Cannot edit sent newsletters.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required',
            'status' => 'required|in:draft,scheduled,send_now',
            'scheduled_at' => 'nullable|date',
            'recipient_type' => 'required|in:all,premium,free,selected',
            'selected_users' => 'nullable|array',
            'selected_users.*' => 'exists:users,id',
        ]);

        $data = [
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => $request->status === 'send_now' ? 'sent' : $request->status,
            'recipient_type' => $request->recipient_type,
            'selected_users' => $request->recipient_type === 'selected' ? $request->selected_users : null,
        ];

        // Get recipients based on type
        $users = $this->getRecipients($request->recipient_type, $request->selected_users);

        // Handle scheduling
        if ($request->status === 'scheduled' && !empty($request->scheduled_at)) {
            $data['scheduled_at'] = Carbon::parse($request->scheduled_at, $request->input('timezone', 'UTC'))->utc();

            // Calculate recipients count for scheduled newsletter
            $data['recipients_count'] = $users->count();
        } elseif ($request->status === 'send_now') {
            // Send immediately
            $count = 0;

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new NewsLetterMail($request->subject, $request->body));
                    $count++;
                } catch (\Exception $e) {
                    // Log error but continue
                }
            }

            $data['sent_at'] = now();
            $data['recipients_count'] = $count;
        }

        // Calculate recipients count if not already set
        if (!isset($data['recipients_count'])) {
            $data['recipients_count'] = $users->count();
        }

        $newsletter->update($data);

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter updated successfully.');
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter deleted successfully.');
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = User::where('role', 'user')
            ->select(['id', 'name', 'email'])
            ->where(function($q) use ($search) {
                if ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                }
            })
            ->orderBy('name');

        $total = $query->count();
        $users = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $results = $users->map(function($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . ' (' . $user->email . ')'
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }
}
