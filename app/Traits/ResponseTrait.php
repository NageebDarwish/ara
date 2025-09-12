<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ResponseTrait
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'payload'    => $result,
        ];


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['payload'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException( $this->sendError('validation_error', $validator->errors()));
    }
}
