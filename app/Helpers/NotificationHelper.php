<?php

namespace App\Helpers;

use Pusher\Pusher;

class NotificationHelper
{
    private static $pusher;

    private static function getPusher()
    {
        if (!isset(self::$pusher)) {
            self::$pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' =>  env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true,
                ]
            );
        }

        return self::$pusher;
    }

   public static function triggerEvent($channel, $event, $message)
    {

        try {
            // \Log::debug("Attempting to trigger Pusher event", [
            //     'channel' => $channel,
            //     'event' => $event,
            //     'message' => $message
            // ]);
    
            $pusher = self::getPusher();
            
            // \Log::debug("Pusher channels list", [
            //     'channels' => $pusher->get_channels()
            // ]);
            

            $result = $pusher->trigger($channel, $event, ['message' => $message]);
            
            // \Log::debug("Pusher trigger result", ['result' => $result]);
            return true;
        } catch (\Exception $e) {
            // \Log::error("Pusher trigger failed", [
            //     'error' => $e->getMessage(),
            //     'channel' => $channel,
            //     'event' => $event
            // ]);
            return false;
        }
    }
}