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


}