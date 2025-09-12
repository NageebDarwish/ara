<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Setting;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Support\Facades\Log;

class RenewSubscription extends Command
{
    protected $signature = 'renew-subscription';
    protected $description = 'Automatically renew subscriptions that are due for renewal';

    public function handle()
    {
        // Log::info('[SubscriptionRenewal] ===== STARTING RENEWAL PROCESS =====');
        $this->info('Starting subscription renewal process');

        try {
            // 1. Load Stripe configuration
            // Log::info('[SubscriptionRenewal] Loading Stripe configuration');
            $this->info('Loading Stripe configuration');

            $setting = Setting::first();
            if (!$setting) {
                $message = 'Settings not found in database';
                // Log::error("[SubscriptionRenewal] {$message}");
                $this->error($message);
                return;
            }

            if (empty($setting->stripe_secret_key)) {
                $message = 'Stripe secret key not configured in settings';
                // Log::error("[SubscriptionRenewal] {$message}");
                $this->error($message);
                return;
            }

            Stripe::setApiKey($setting->stripe_secret_key);
            // Log::info('[SubscriptionRenewal] Stripe API configured successfully');
            $this->info('Stripe API configured');

            // 2. Fetch subscriptions due for renewal
            // Log::info('[SubscriptionRenewal] Querying subscriptions due for renewal');
            $this->info('Querying subscriptions...');

            $subscriptions = Subscription::with('plan')
                ->where('is_renewable', 1)
                ->where('end_date', '<=', now())
                ->whereNotNull('stripe_customer_id')
                ->whereHas('plan', function($q) {
                    $q->where('is_default', 0);
                })
                ->get();

            $subscriptionCount = $subscriptions->count();
            // Log::info("[SubscriptionRenewal] Found {$subscriptionCount} subscriptions to process");
            $this->info("Found {$subscriptionCount} subscriptions to process");

            if ($subscriptionCount === 0) {
                // Log::info('[SubscriptionRenewal] No subscriptions require renewal');
                $this->info('No subscriptions require renewal');
                return;
            }

            // 3. Process each subscription
            foreach ($subscriptions as $subscription) {
                $subscriptionId = $subscription->id;
                $customerId = $subscription->stripe_customer_id;

                // Log::info("[SubscriptionRenewal] Processing subscription ID: {$subscriptionId}, Customer ID: {$customerId}");
                $this->info("Processing subscription ID: {$subscriptionId}");

                try {
                    // 3.1 Retrieve customer from Stripe
                    // Log::info("[SubscriptionRenewal] Retrieving Stripe customer: {$customerId}");
                    $customer = Customer::retrieve($customerId);

                    if (!$customer || $customer->isDeleted()) {
                        $message = "Customer {$customerId} not found or deleted in Stripe";
                        // Log::error("[SubscriptionRenewal] {$message}");
                        $this->error($message);
                        continue;
                    }

                    // 3.2 Check for payment method
                    if (empty($customer->invoice_settings->default_payment_method)) {
                        $message = "Customer {$customerId} has no default payment method";
                        // Log::warning("[SubscriptionRenewal] {$message}");
                        $this->warn($message);
                        continue;
                    }

                    $paymentMethodId = $customer->invoice_settings->default_payment_method;
                    // Log::info("[SubscriptionRenewal] Found payment method: {$paymentMethodId}");

                    // 3.3 Calculate renewal amount
                    $amount = $subscription->plan->cycle === 'yearly'
                        ? $subscription->plan->price * 12
                        : $subscription->plan->price;

                    $amountInCents = $amount * 100;
                    // Log::info("[SubscriptionRenewal] Calculated amount: {$amount} ({$amountInCents} cents)");

                    // 3.4 Create and confirm PaymentIntent
                    // Log::info("[SubscriptionRenewal] Creating PaymentIntent for {$amountInCents} cents");
                    $this->info("Attempting payment of {$amount} USD");

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'amount' => $amountInCents,
                        'currency' => 'usd',
                        'customer' => $customerId,
                        'payment_method' => $paymentMethodId,
                        'off_session' => true,
                        'confirm' => true,
                        'description' => "Renewal for subscription {$subscriptionId}",
                    ]);

                    // Log::info("[SubscriptionRenewal] PaymentIntent created: {$paymentIntent->id}, Status: {$paymentIntent->status}");
                    $this->info("Payment initiated: {$paymentIntent->id}");

                    // 3.5 Update subscription dates
                    $newEndDate = $subscription->plan->cycle === 'monthly'
                        ? now()->addMonth()
                        : now()->addYear();

                    $subscription->update([
                        'start_date' => now(),
                        'end_date' => $newEndDate,
                        'last_payment_at' => now(),
                    ]);

                    // Log::info("[SubscriptionRenewal] Subscription {$subscriptionId} renewed until {$newEndDate}");
                    $this->info("Successfully renewed until {$newEndDate}");

                } catch (\Stripe\Exception\CardException $e) {
                    $errorMsg = "Card declined for subscription {$subscriptionId}: " . $e->getMessage();
                    // Log::error("[SubscriptionRenewal] {$errorMsg}");
                    $this->error($errorMsg);

                    // Handle specific card errors (e.g., insufficient funds)
                    $declineCode = $e->getDeclineCode() ?? 'unknown';
                    // Log::error("[SubscriptionRenewal] Decline code: {$declineCode}");

                } catch (\Stripe\Exception\RateLimitException $e) {
                    $errorMsg = "Stripe rate limit exceeded for subscription {$subscriptionId}";
                    // Log::error("[SubscriptionRenewal] {$errorMsg}: " . $e->getMessage());
                    $this->error($errorMsg);

                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $errorMsg = "Invalid Stripe request for subscription {$subscriptionId}";
                    // Log::error("[SubscriptionRenewal] {$errorMsg}: " . $e->getMessage());
                    $this->error($errorMsg);

                } catch (\Exception $e) {
                    $errorMsg = "Unexpected error processing subscription {$subscriptionId}";
                    // Log::error("[SubscriptionRenewal] {$errorMsg}: " . $e->getMessage());
                    $this->error($errorMsg);
                }
            }

            // Log::info('[SubscriptionRenewal] ===== RENEWAL PROCESS COMPLETED =====');
            $this->info('Subscription renewal process completed');

        } catch (\Exception $e) {
            $errorMsg = 'Critical error in subscription renewal process: ' . $e->getMessage();
            // Log::error("[SubscriptionRenewal] {$errorMsg}");
            $this->error($errorMsg);
        }
    }
}
