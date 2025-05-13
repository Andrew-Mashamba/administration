<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BillPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BillsPaymentController extends Controller
{
    protected $billPaymentService;

    public function __construct(BillPaymentService $billPaymentService)
    {
        $this->billPaymentService = $billPaymentService;
    }

    /**
     * Handle bill inquiry request from NBC
     */
    public function inquiry(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'channelId' => 'required|string',
                'spCode' => 'required|string',
                'requestType' => 'required|string|in:inquiry',
                'timestamp' => 'required|date',
                'userId' => 'required|string',
                'branchCode' => 'required|string',
                'channelRef' => 'required|string',
                'billRef' => 'required|string',
                'extraFields' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'statusCode' => '601',
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'spCode' => $request->spCode,
                    'channelId' => $request->channelId,
                    'requestType' => 'inquiry',
                    'channelRef' => $request->channelRef,
                    'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                    'data' => []
                ], 422);
            }


            // Get bill details from service
            $billDetails = $this->billPaymentService->getBillDetails($request->billRef);

           

        } catch (\Exception $e) {
            Log::error('Bills Payment Inquiry Error: ' . $e->getMessage());
            return response()->json([
                'statusCode' => '699',
                'message' => 'Exception caught: ' . $e->getMessage(),
                'spCode' => $request->spCode ?? '',
                'channelId' => $request->channelId ?? '',
                'requestType' => 'inquiry',
                'channelRef' => $request->channelRef ?? '',
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'data' => []
            ], 500);
        }
    }

    /**
     * Handle payment notification from NBC
     */
    public function payment(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'channelId' => 'required|string',
                'spCode' => 'required|string',
                'requestType' => 'required|string|in:payment',
                'approach' => 'required|string|in:async,sync',
                'callbackUrl' => 'required_if:approach,async|string',
                'timestamp' => 'required|string',
                'userId' => 'required|string',
                'branchCode' => 'required|string',
                'billRef' => 'required|string',
                'channelRef' => 'required|string',
                'amount' => 'required|string',
                'creditAccount' => 'required|string',
                'creditCurrency' => 'required|string',
                'paymentType' => 'required|string|in:ACCOUNT,Cash',
                'channelCode' => 'required|string',
                'payerName' => 'required|string',
                'payerPhone' => 'required|string',
                'payerEmail' => 'required|email',
                'narration' => 'required|string',
                'extraFields' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'statusCode' => '602',
                    'message' => 'Validation Failed: ' . $validator->errors()->first(),
                    'spCode' => $request->spCode,
                    'channelId' => $request->channelId,
                    'requestType' => 'payment',
                    'channelRef' => $request->channelRef,
                    'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                    'paymentDetails' => null
                ], 400);
            }

            // Process payment
            $payment = $this->billPaymentService->processPayment($request->all());

            // For async approach, return immediate acknowledgment
            if ($request->approach === 'async') {
                return response()->json([
                    'statusCode' => '600',
                    'message' => 'Received and validated, engine is now processing your request',
                    'channelId' => $request->channelId,
                    'spCode' => $request->spCode,
                    'requestType' => 'payment',
                    'channelRef' => $request->channelRef,
                    'gatewayRef' => $payment->gateway_ref,
                    'billerReceipt' => $payment->biller_receipt,
                    'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                    'paymentDetails' => null
                ]);
            }

            // For sync approach, return final response
            return response()->json([
                'statusCode' => '600',
                'message' => 'Success',
                'channelId' => $request->channelId,
                'spCode' => $request->spCode,
                'requestType' => 'payment',
                'channelRef' => $request->channelRef,
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'paymentDetails' => [
                    'billRef' => $payment->bill_ref,
                    'gatewayRef' => $payment->gateway_ref,
                    'amount' => $payment->amount,
                    'currency' => $payment->credit_currency,
                    'transactionTime' => $payment->transaction_time->format('Ymd\THis'),
                    'billerReceipt' => $payment->biller_receipt,
                    'remarks' => 'Successfully received',
                    'extraFields' => $payment->extra_fields
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Bills Payment Error: ' . $e->getMessage());
            return response()->json([
                'statusCode' => '699',
                'message' => 'Exception caught: ' . $e->getMessage(),
                'spCode' => $request->spCode ?? '',
                'channelId' => $request->channelId ?? '',
                'requestType' => 'payment',
                'channelRef' => $request->channelRef ?? '',
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'paymentDetails' => null
            ], 500);
        }
    }

    /**
     * Handle status check request from NBC
     */
    public function statusCheck(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'channelId' => 'required|string',
                'spCode' => 'required|string',
                'requestType' => 'required|string|in:statusCheck',
                'timestamp' => 'required|string',
                'channelRef' => 'required|string',
                'billRef' => 'required|string',
                'extraFields' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'statusCode' => '602',
                    'message' => 'Validation Failed: ' . $validator->errors()->first(),
                    'spCode' => $request->spCode,
                    'channelId' => $request->channelId,
                    'requestType' => 'statusCheck',
                    'channelRef' => $request->channelRef,
                    'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                    'paymentDetails' => null
                ], 400);
            }

            // Get payment status
            $paymentDetails = $this->billPaymentService->getPaymentStatus(
                $request->channelRef,
                $request->billRef
            );

            return response()->json([
                'statusCode' => '600',
                'message' => 'Success',
                'channelId' => $request->channelId,
                'spCode' => $request->spCode,
                'requestType' => 'statusCheck',
                'channelRef' => $request->channelRef,
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'paymentDetails' => $paymentDetails
            ]);

        } catch (\Exception $e) {
            Log::error('Bills Payment Status Check Error: ' . $e->getMessage());
            return response()->json([
                'statusCode' => '699',
                'message' => 'Exception caught: ' . $e->getMessage(),
                'spCode' => $request->spCode ?? '',
                'channelId' => $request->channelId ?? '',
                'requestType' => 'statusCheck',
                'channelRef' => $request->channelRef ?? '',
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'paymentDetails' => null
            ], 500);
        }
    }
}
