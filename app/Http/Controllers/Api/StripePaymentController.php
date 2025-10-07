<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe\PaymentIntent;
use App\Models\Setting;
use Stripe\Customer;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Webhook;

class StripePaymentController extends Controller
{


    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'price'=>'required',
             'payment_method_id' => 'required'
            ]);

        $setting=Setting::first();
        $secretKey=$setting->stripe_secret_key;
        try {
               Stripe::setApiKey($secretKey);
         $customer = Customer::create([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);

                // Attach payment method to customer
                $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method_id);
                $paymentMethod->attach(['customer' => $customer->id]);

                // Set as default payment method
                Customer::update($customer->id, [
                    'invoice_settings' => [
                        'default_payment_method' => $paymentMethod->id
                    ]
                ]);

             $intent = \Stripe\PaymentIntent::create([
                 'amount' => $request->price * 100,
                 'currency' => 'usd',
                 'customer' => $customer->id,
                 'payment_method' => $paymentMethod->id,
                'off_session' => false,
                'confirm' => false,
             ]);
               return response()->json([
            'clientSecret' => $intent->client_secret,
            'customerId' => $customer->id,
            'paymentMethodId' => $paymentMethod->id
        ]);
        }
         catch (\Exception $e) {
             return response()->json(['error' => $e->getMessage()], 500);
         }
    }


    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'success_url' => 'nullable|url',
            'cancel_url' => 'nullable|url',
            'currency' => 'nullable|string|size:3'
        ]);

        $setting = Setting::first();
        $secretKey = $setting?->stripe_secret_key;

        if (!$secretKey) {
            return response()->json(['error' => 'Stripe is not configured.'], 500);
        }

        $plan = Plan::findOrFail($request->plan_id);

        try {
            Stripe::setApiKey($secretKey);

            $providedSuccess = $request->input('success_url');
            if ($providedSuccess) {
                $successUrl = $providedSuccess . (str_contains($providedSuccess, '?') ? '&' : '?') . 'session_id={CHECKOUT_SESSION_ID}';
            } else {
                $successUrl = rtrim(config('app.url'), '/') . '/payment/success?session_id={CHECKOUT_SESSION_ID}';
            }
            $cancelUrl = $request->input('cancel_url') ?: rtrim(config('app.url'), '/') . '/payment/cancel';
            $currency = strtolower($request->input('currency', 'usd'));
            $amountCents = (int) round($plan->price * 100);

            $session = CheckoutSession::create([
                'mode' => 'payment',
                'customer_email' => $request->email,
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $plan->name,
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'plan_id' => (string)$plan->id,
                    'email' => $request->email,
                    'user_id' => (string)($request->input('user_id') ?: (Auth::id() ?: '')),
                ],
            ]);

            return response()->json([
                'url' => $session->url,
                'id' => $session->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $setting = Setting::first();
        // Prefer env for security; fallback to settings if you store it there
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        if (!$endpointSecret) {
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            /** @var \Stripe\Checkout\Session $session */
            $session = $event->data->object;

            $planId = (int)($session->metadata['plan_id'] ?? 0);
            $userId = (int)($session->metadata['user_id'] ?? 0);
            $email = $session->customer_email ?? ($session->metadata['email'] ?? null);

            if ($planId) {
                try {
                    $plan = Plan::findOrFail($planId);
                    $now = Carbon::now();
                    $end = match ($plan->cycle) {
                        'yearly', 'annual', 'annually' => $now->copy()->addYear(),
                        'quarterly' => $now->copy()->addQuarter(),
                        'weekly' => $now->copy()->addWeek(),
                        default => $now->copy()->addMonth(),
                    };

                    // Resolve user id: prefer metadata user_id; otherwise try by email
                    if (!$userId && $email) {
                        $user = \App\Models\User::where('email', $email)->first();
                        $userId = $user?->id ?? 0;
                    }

                    if ($userId) {
                        Subscription::updateOrCreate(
                            ['user_id' => $userId],
                            [
                                'plan_id' => $plan->id,
                                'amount' => $plan->price,
                                'start_date' => $now,
                                'end_date' => $end,
                                'status' => 'active',
                                'is_renewable' => true,
                            ]
                        );
                    }
                } catch (\Throwable $e) {
                    // Log and continue; webhook should still acknowledge
                    logger()->error('Stripe webhook processing failed: ' . $e->getMessage());
                }
            }
        }

        return response('ok', 200);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $setting = Setting::first();
        $secretKey = $setting?->stripe_secret_key;
        if (!$secretKey) {
            return response()->json(['error' => 'Stripe is not configured.'], 500);
        }

        try {
            Stripe::setApiKey($secretKey);
            $session = CheckoutSession::retrieve($request->session_id);

            if ($session->payment_status !== 'paid') {
                return response()->json(['error' => 'Payment not completed'], 400);
            }

            // Get plan from metadata
            $planId = (int)($session->metadata['plan_id'] ?? 0);
            if (!$planId) {
                return response()->json(['error' => 'Missing plan metadata'], 400);
            }
            $plan = Plan::findOrFail($planId);

            $now = Carbon::now();
            $end = match ($plan->cycle) {
                'yearly', 'annual', 'annually' => $now->copy()->addYear(),
                'quarterly' => $now->copy()->addQuarter(),
                'weekly' => $now->copy()->addWeek(),
                default => $now->copy()->addMonth(), // monthly by default
            };

            // Upsert subscription
            $subscription = Subscription::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'plan_id' => $plan->id,
                    'amount' => $plan->price,
                    'start_date' => $now,
                    'end_date' => $end,
                    'status' => 'active',
                    'is_renewable' => true,
                ]
            );

            return response()->json([
                'success' => true,
                'subscription' => $subscription,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}

