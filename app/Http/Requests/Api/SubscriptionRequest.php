<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id'=>'required|exists:users,id',
            'plan_id'=>'required|exists:plans,id',
            'amount'=>'required',
            // 'card_number'=>'required',
            // 'card_type'=>'required',
            // 'expiry_date'=>'required',
            // 'cardholder_name'=>'required',
            // 'cvv'=>'required',
            'stripe_customer_id'=>'required',
        ];
    }
}
