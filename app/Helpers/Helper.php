<?php


namespace App\Helpers;


class Helper
{
    public static function sendSeverError($error, $errorMessages = [], $code = 404)
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
}
