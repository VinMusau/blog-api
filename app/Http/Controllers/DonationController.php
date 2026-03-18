<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MpesaService;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
   
    protected $mpesa; 
    public function __construct(MpesaService $mpesa)
    {
        $this->mpesa = $mpesa; // inject the service
    }
    
    /**
     * Trigger the stk push.
     */
    public function donate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|string',
        ]);

        $phone = preg_replace('/^0/', '254', $request->phone); // standardize phone to 2547xxxx
        $reference = 'COFFEE' . strtoupper(Str::random(5)); 

        try {
            $response =$this->mpesa->stkPush($phone, $request->amount, $reference);

            // to check if safaricom accepted the request
            if (isset($response['ResponseCode']) && (string)$response['ResponseCode'] === '0') {
                // save the donation with pending status
                Donation::create([
                    'user_id' => auth()->id(),
                    'amount' => $request->amount,
                    'phone' => $phone,
                    'reference' => $reference,
                    'merchant_request_id' => $response['MerchantRequestID'],
                    'checkout_request_id' => $response['CheckoutRequestID'],
                    'status' => 'pending',
                ]);

                return response()->json(['message' => 'STK Push initiated. Please complete the payment on your phone.']);
            } 

            return response()->json([
                'message' => $response['errorMessage'] ?? 'Failed to initiate STK Push',
                'details' => $response
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Mpesa Service Failed',
                'error' => $e->getMessage()
            ], 500);
        }
        
    
    }

    /**
     * Handle callback from safaricom after payment is completed.
     */
    public function callback(Request $request)
    {
        
        
        $content = $request->all();
        Log::info('MPESA Callback Received' , $content);

        try {
            $stkCallback = $content['Body']['stkCallback'];
            $checkoutRequestId = $stkCallback['CheckoutRequestID'];
            $resultCode = $stkCallback['ResultCode'];

            $donation = Donation::where('checkout_request_id', $checkoutRequestId)->first();

            if (!$donation) {
                Log::error('Donation record not found for CheckoutRequestID: ' . $checkoutRequestId);
                return response()->json([
                    'ResultCode' => 1,
                    'message' => 'Donation record not found for this callback'
                ]);
            }

            if ($resultCode === 0) {
                $metadata = $stkCallback['CallbackMetadata']['Item'];

                $receipt = collect($metadata)->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

                $donation->update([
                    'status' => 'completed',
                    'mpesa_receipt' => $receipt,
                    'completed_at' => now(),
                ]);

                Log::info("Donation $checkoutRequestId marked as COMPLETED");
            } else {
                $donation->update(['status' => 'failed']);
                Log::error("Donation $checkoutRequestId marked as FAILED. ResultCode: $resultCode");
            }

            return response()->json(['ResultCode' => 0, 'message' => 'Callback received']);
         } catch (\Exception $e) {
            Log::error('Error processing MPESA callback: ' . $e->getMessage());
            return response()->json([
                'ResultCode' => 1,
                'message' => 'Error processing callback'
            ], 500);
        }    
    }
}
