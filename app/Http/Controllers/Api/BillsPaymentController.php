<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BillPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class BillsPaymentController extends Controller
{
    protected $billPaymentService;

    public function __construct(BillPaymentService $billPaymentService)
    {
        $this->billPaymentService = $billPaymentService;
    }

    /**
     * Handle bill inquiry request from NBC via GET
     */
    public function inquiryGet($institution_id, $member_id)
    {
        try {
            Log::info('Received GET inquiry request', [
                'institution_id' => $institution_id,
                'member_id' => $member_id
            ]);

            // Get bill details from service
            $billDetails = $this->billPaymentService->getBillDetails($member_id);

            return response()->json([
                'statusCode' => '600',
                'message' => 'Success',
                'spCode' => $institution_id,
                'channelId' => 'WEB',
                'requestType' => 'inquiry',
                'channelRef' => $member_id,
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'data' => $billDetails
            ]);

        } catch (\Exception $e) {
            Log::error('Bills Payment Inquiry Error: ' . $e->getMessage());
            return response()->json([
                'statusCode' => '699',
                'message' => 'Exception caught: ' . $e->getMessage(),
                'spCode' => $institution_id,
                'channelId' => 'WEB',
                'requestType' => 'inquiry',
                'channelRef' => $member_id,
                'timestamp' => now()->format('Y-m-d\TH:i:s.u'),
                'data' => []
            ], 500);
        }
    }

    /**
     * Handle bill inquiry request from NBC
     */
    public function inquiry(Request $request)
    {
        try {
            Log::info('Received inquiry request', [
                'request_id' => uniqid(),
                'request_data' => $request->all()
            ]);

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
                Log::warning('Validation failed for inquiry request', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);

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

            // Parse bill reference number
            $billRef = $request->billRef;
            $controlNumber = (string) $billRef;

            try {
                // Extract parts from control number
                $categoryCode = (string) substr($controlNumber, 0, 1);   // character 0
                $sacco = (string) substr($controlNumber, 1, 4);          // characters 1-4
                $member = (string) substr($controlNumber, 5, 5);         // characters 5-9
                $service = (string) substr($controlNumber, 10, 1);       // character 10
                $isRecurring = (string) substr($controlNumber, 11, 1);   // character 11
                $paymentMode = (string) substr($controlNumber, 12, 1);   // character 12

                Log::info('Parsed bill reference', [
                    'billRef' => $billRef,
                    'parsed_data' => [
                        'categoryCode' => $categoryCode,
                        'sacco' => $sacco,
                        'member' => $member,
                        'service' => $service,
                        'isRecurring' => $isRecurring,
                        'paymentMode' => $paymentMode
                    ]
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to parse bill reference', [
                    'billRef' => $billRef,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Invalid bill reference format');
            }

            // Get subdomain from database
            try {
                $institution = DB::table('institutions')
                    ->where('institution_id', $sacco)
                    ->first();

                if (!$institution) {
                    Log::error('Institution not found', ['sacco_id' => $sacco]);
                    throw new \Exception('Institution not found');
                }

                $subdomain = $institution->alias;
                Log::info('Found institution subdomain', [
                    'sacco_id' => $sacco,
                    'subdomain' => $subdomain
                ]);

            } catch (\Exception $e) {
                Log::error('Database error while fetching institution', [
                    'sacco_id' => $sacco,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Failed to fetch institution details');
            }

            // Construct target URL
            //$targetUrl = sprintf('http://%s.127.0.0.1:8000/api/bills-payments-api/api/v1/inquiry', $subdomain);
            
            $targetUrl = 'http://127.0.0.1:8000/api/billing/inquiry';
            Log::info('Forwarding request to target endpoint', [
                'target_url' => $targetUrl,
                'request_data' => $request->all()
            ]);

            // Forward the request to the target endpoint
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Forwarded-For' => $request->ip(),
                'X-Original-Request-ID' => uniqid()
            ])->timeout(30)->post($targetUrl, $request->all());

            // Log the response
            Log::info('Received response from target endpoint', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error('Target endpoint returned error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
            }

            // Return the response from the target endpoint
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('Bills Payment Inquiry Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
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
            Log::info('Received payment request', [
                'request_id' => uniqid(),
                'request_data' => $request->all()
            ]);

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
                Log::warning('Validation failed for payment request', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);

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

            // Parse bill reference number
            $billRef = $request->billRef;
            $controlNumber = (string) $billRef;

            try {
                // Extract parts from control number
                $categoryCode = (string) substr($controlNumber, 0, 1);   // character 0
                $sacco = (string) substr($controlNumber, 1, 4);          // characters 1-4
                $member = (string) substr($controlNumber, 5, 5);         // characters 5-9
                $service = (string) substr($controlNumber, 10, 1);       // character 10
                $isRecurring = (string) substr($controlNumber, 11, 1);   // character 11
                $paymentMode = (string) substr($controlNumber, 12, 1);   // character 12

                Log::info('Parsed bill reference', [
                    'billRef' => $billRef,
                    'parsed_data' => [
                        'categoryCode' => $categoryCode,
                        'sacco' => $sacco,
                        'member' => $member,
                        'service' => $service,
                        'isRecurring' => $isRecurring,
                        'paymentMode' => $paymentMode
                    ]
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to parse bill reference', [
                    'billRef' => $billRef,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Invalid bill reference format');
            }

            // Get subdomain from database
            try {
                $institution = DB::table('institutions')
                    ->where('institution_id', $sacco)
                    ->first();

                if (!$institution) {
                    Log::error('Institution not found', ['sacco_id' => $sacco]);
                    throw new \Exception('Institution not found');
                }

                $subdomain = $institution->alias;
                Log::info('Found institution subdomain', [
                    'sacco_id' => $sacco,
                    'subdomain' => $subdomain
                ]);

            } catch (\Exception $e) {
                Log::error('Database error while fetching institution', [
                    'sacco_id' => $sacco,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Failed to fetch institution details');
            }

            // Construct target URL
            $targetUrl = 'http://127.0.0.1:8000/api/billing/payment-notify';
            
            Log::info('Forwarding payment request to target endpoint', [
                'target_url' => $targetUrl,
                'request_data' => $request->all()
            ]);

            // Forward the request to the target endpoint
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Forwarded-For' => $request->ip(),
                'X-Original-Request-ID' => uniqid()
            ])->timeout(30)->post($targetUrl, $request->all());

            // Log the response
            Log::info('Received response from target endpoint', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error('Target endpoint returned error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
            }

            // Return the response from the target endpoint
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('Bills Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
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
            Log::info('Received status check request', [
                'request_id' => uniqid(),
                'request_data' => $request->all()
            ]);

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
                Log::warning('Validation failed for status check request', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);

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

            // Parse bill reference number
            $billRef = $request->billRef;
            $controlNumber = (string) $billRef;

            try {
                // Extract parts from control number
                $categoryCode = (string) substr($controlNumber, 0, 1);   // character 0
                $sacco = (string) substr($controlNumber, 1, 4);          // characters 1-4
                $member = (string) substr($controlNumber, 5, 5);         // characters 5-9
                $service = (string) substr($controlNumber, 10, 1);       // character 10
                $isRecurring = (string) substr($controlNumber, 11, 1);   // character 11
                $paymentMode = (string) substr($controlNumber, 12, 1);   // character 12

                Log::info('Parsed bill reference', [
                    'billRef' => $billRef,
                    'parsed_data' => [
                        'categoryCode' => $categoryCode,
                        'sacco' => $sacco,
                        'member' => $member,
                        'service' => $service,
                        'isRecurring' => $isRecurring,
                        'paymentMode' => $paymentMode
                    ]
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to parse bill reference', [
                    'billRef' => $billRef,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Invalid bill reference format');
            }

            // Get subdomain from database
            try {
                $institution = DB::table('institutions')
                    ->where('institution_id', $sacco)
                    ->first();

                if (!$institution) {
                    Log::error('Institution not found', ['sacco_id' => $sacco]);
                    throw new \Exception('Institution not found');
                }

                $subdomain = $institution->alias;
                Log::info('Found institution subdomain', [
                    'sacco_id' => $sacco,
                    'subdomain' => $subdomain
                ]);

            } catch (\Exception $e) {
                Log::error('Database error while fetching institution', [
                    'sacco_id' => $sacco,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Failed to fetch institution details');
            }

            // Construct target URL
            $targetUrl = 'http://127.0.0.1:8000/api/billing/status-check';
            
            Log::info('Forwarding status check request to target endpoint', [
                'target_url' => $targetUrl,
                'request_data' => $request->all()
            ]);

            // Forward the request to the target endpoint
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Forwarded-For' => $request->ip(),
                'X-Original-Request-ID' => uniqid()
            ])->timeout(30)->post($targetUrl, $request->all());

            // Log the response
            Log::info('Received response from target endpoint', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error('Target endpoint returned error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
            }

            // Return the response from the target endpoint
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('Bills Payment Status Check Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
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


    public function logCallBack(Request $request)
    {
        Log::info('Received call back request', [
            'request_id' => uniqid(),
            'request_data' => $request->all()
        ]);
    }
}
