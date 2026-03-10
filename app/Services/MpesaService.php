<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Donation;
use App\Models\User;
class MpesaService
{
    protected $baseUrl;

    public function __construct()
    {
        // Use sandbox for testing. Switch to https://api.safaricom.co.ke for production.
        $this->baseUrl = 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Generate the OAuth Access Token
     */
    public function getAccessToken()
    {
        $credentials = base64_encode(config('services.mpesa.key') . ':' . config('services.mpesa.secret'));

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        if ($response->failed()) {
            Log::error("M-Pesa Access Token Error: " . $response->body());
            return null;
        }

        return $response->json()['access_token'];
    }

    /**
     * Initiate STK Push request
     */
    public function stkPush($phone, $amount, $reference)
    {
        $token = $this->getAccessToken();
        if (!$token) return ['ResponseCode' => '1', 'CustomerMessage' => 'Auth Failed'];

        $timestamp = now()->format('YmdHis');
        $password = base64_encode(config('services.mpesa.shortcode') . config('services.mpesa.passkey') . $timestamp);

        $response = Http::withToken($token)
            ->timeout(59)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => config('services.mpesa.shortcode'),
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phone,
                'PartyB' => config('services.mpesa.shortcode'),
                'PhoneNumber' => $phone,
                'CallBackURL' => config('services.mpesa.callback'),
                'AccountReference' => $reference,
                'TransactionDesc' => 'Coffee Donation'
        ]);

        return $response->json();
    }
}