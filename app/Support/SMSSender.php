<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Http\Client\RequestException;

trait SMSSender
{
    function sendWelcomeSMS($phone, $password)
    {
        // Prepare the welcome SMS text
        $welcomeSMS = sprintf("Welcome to %s! 🗳️ Your account is set up!

Head to %s and login

Username: %s
Temp Password: %s

Cast your vote now! Need help? Call/WhatsApp support at %s. Happy voting! 🎉

 Dial *456*9*5# to stop", config('app.name'), route('login'), $phone, $password, '+254711347184');

        if (env('SMS_LIVE')) {
            try {
                $this->moveSMS($phone, $welcomeSMS);
                $this->logToSmsLog($welcomeSMS);
            } catch (\Throwable $th) {
                try {
                    $this->mobitechSMS($phone, $welcomeSMS);
                    $this->logToSmsLog($welcomeSMS);
                } catch (\Throwable $th) {
                    try {
                        $this->sendAfrikasTalking($phone, $welcomeSMS);
                        $this->logToSmsLog($welcomeSMS);
                    } catch (\Exception $e) {
                        //
                    }
                }
            }
        } else {
            $this->logToSmsLog($welcomeSMS);
        }
    }

    private function logToSmsLog($logMessage)
    {
        // Get the current date and time for the log entry
        $timestamp = now()->toDateTimeString();

        // Format the log entry
        $logEntry = "==============================\n\n[$timestamp] $logMessage\n\n";

        // Append the log entry to the SMS log file
        $logFile = storage_path('logs/sms_log.txt');
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    protected function genPassword(): string
    {
        $password = range(0, 9);
        if (shuffle($password)) {
            $password = array_splice($password, 0, 4);
            $final = implode($password);
            return $final;
        }
        return "0000";
    }

    function mobitechSMS($phone, $message)
    {
        $data = json_encode([
            'api_key' => env('MOBITECH_API_KEY'),
            'username' => env('MOBITECH_USER_NAME'),
            'sender_id' => env('MOBITECH_SENDER_ID'),
            'message' => $message,
            'phone' => $phone,
        ]);

        $ch = curl_init(env('MOBITECH_BASE_URL') . 'api/sendsms');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $output = curl_error($ch);
            $this->logToSmsLog($output . "\n\n" . $message);
            throw new \Exception($output, 1);
        } else {
            $this->logToSmsLog($message);
        }

        curl_close($ch);
    }

    function moveSMS($phone, $message)
    {
        // movesms
        $username = env('SMS_USERNAME');
        $apiKey = env('SMS_KEY');
        $sender = env('SMS_SENDER_ID');
        $to = $phone;
        $message = $message;
        $msgtype = 5;
        $dlr = 1;

        $url = 'https://sms.movesms.co.ke/api/compose?' . http_build_query([
            'username' => $username,
            'api_key' => $apiKey,
            'sender' => $sender,
            'to' => $to,
            'message' => $message,
            'msgtype' => $msgtype,
            'dlr' => $dlr,
        ]);

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
            } else {
                $statusCode = $response->status();
                $errorMessage = $response->body();
                // dd(["Error 1:", $errorMessage, $statusCode]);
                // Handle the error accordingly
                throw new \Exception($errorMessage, 1);
            }
        } catch (RequestException $e) {
            // Handle exceptions related to the HTTP request itself
            $statusCode = $e->getCode(); // Get the HTTP status code (if available)
            $errorMessage = $e->getMessage(); // Get the error message
            // dd(["Error 2:", $errorMessage, $statusCode]);
            // Handle the exception accordingly
            throw new \Exception($errorMessage, 1);
        } catch (\Exception $e) {
            // Handle any other general exceptions
            $errorMessage = $e->getMessage(); // Get the error message
            // dd(["Error 3:", $errorMessage]);
            // Handle the exception accordingly
            throw $e;
        }
    }

    function sendAfrikasTalking($phone, $message)
    {
        // Set your app credentials
        $username   = env('AP_USERNAME');
        $apiKey     = env('AP_API_KEY');
        $from       = env('AP_SENDER_ID');

        // Initialize the SDK
        $AT         = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        // Set your shortCode or senderId
        // $from       = "myShortCode or mySenderId";

        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $phone,
                'message' => $message,
                'from'    => $from
            ]);

            $this->logToSmsLog($message);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
