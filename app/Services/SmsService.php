<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendSmsOtp($otp)
    {
        // Sanitize inputs
        $countryCode = preg_replace('/\D/', '', $otp->phone_country_code ?? '91');
        $phoneNumber = preg_replace('/\D/', '', $otp->mobile);
        $formattedPhone = $countryCode . $phoneNumber;

        // Extract actual OTP value
        $otpValue = $otp->otp;

        // Build URL safely
        $url = sprintf(
            'http://api.simpel.ai/api/send-otp/%s/%s',
            $formattedPhone,
            $otpValue
        );

        try {
            $response = Http::timeout(5)
                ->retry(2, 100)
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            // Log API failure
            Log::error('SMS API failed', [
                'url' => $url,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('SMS sending exception', [
                'message' => $e->getMessage(),
                'url' => $url,
            ]);

            return false;
        }
    }
}
