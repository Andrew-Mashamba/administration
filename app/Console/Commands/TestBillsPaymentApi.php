<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestBillsPaymentApi extends Command
{
    protected $signature = 'test:bills-payment-api';
    protected $description = 'Test the Bills Payment API endpoints';

    public function handle()
    {
        $baseUrl = config('app.url') . '/api';
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.bills_payment.jwt_token'),
            'Digital-Signature' => config('services.bills_payment.digital_signature'),
            'Timestamp' => gmdate('Y-m-d\TH:i:s.000\Z')
        ];

        // 1. Test Inquiry API
        $this->info('Testing Inquiry API...');
        $inquiryResponse = Http::withHeaders($headers)
            ->post($baseUrl . '/bills-payments-api/api/v1/inquiry', [
                'channelId' => 'CBP1010101',
                'spCode' => 'BPE0001000BC',
                'requestType' => 'inquiry',
                'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
                'userId' => 'USER101',
                'branchCode' => '015',
                'channelRef' => '520DAN18311100298',
                'billRef' => 'PE0123456789',
                'extraFields' => []
            ]);

        $this->displayResponse($inquiryResponse);

        // 2. Test Payment API (Async)
        $this->info('Testing Payment API (Async)...');
        $asyncPaymentResponse = Http::withHeaders($headers)
            ->post($baseUrl . '/bills-payments-api/api/v1/payment', [
                'channelId' => 'CBP1010101',
                'spCode' => 'BPE0001000BC',
                'requestType' => 'payment',
                'approach' => 'async',
                'callbackUrl' => config('app.url') . '/api/callback',
                'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
                'userId' => 'USER101',
                'branchCode' => '015',
                'billRef' => 'PE0123456789',
                'channelRef' => '520DAN183111002',
                'amount' => '25000',
                'creditAccount' => '0122****1486',
                'creditCurrency' => 'TZS',
                'paymentType' => 'ACCOUNT',
                'channelCode' => 'APP',
                'payerName' => 'John Doe',
                'payerPhone' => '255715000000',
                'payerEmail' => 'john.doe@example.com',
                'narration' => 'Test Bills Payment',
                'extraFields' => []
            ]);

        $this->displayResponse($asyncPaymentResponse);

        // 3. Test Payment API (Sync)
        $this->info('Testing Payment API (Sync)...');
        $syncPaymentResponse = Http::withHeaders($headers)
            ->post($baseUrl . '/bills-payments-api/api/v1/payment', [
                'channelId' => 'CBP1010101',
                'spCode' => 'BPE0001000BC',
                'requestType' => 'payment',
                'approach' => 'sync',
                'callbackUrl' => '',
                'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
                'userId' => 'USER101',
                'branchCode' => '015',
                'billRef' => 'PE0123456789',
                'channelRef' => '520DAN183111003',
                'amount' => '25000',
                'creditAccount' => '0122****1486',
                'creditCurrency' => 'TZS',
                'paymentType' => 'ACCOUNT',
                'channelCode' => 'APP',
                'payerName' => 'John Doe',
                'payerPhone' => '255715000000',
                'payerEmail' => 'john.doe@example.com',
                'narration' => 'Test Bills Payment',
                'extraFields' => []
            ]);

        $this->displayResponse($syncPaymentResponse);

        // 4. Test Status Check API
        $this->info('Testing Status Check API...');
        $statusCheckResponse = Http::withHeaders($headers)
            ->post($baseUrl . '/bills-payments-api/api/v1/status-check', [
                'channelId' => 'CBP1010101',
                'spCode' => 'BPE0001000BC',
                'requestType' => 'statusCheck',
                'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
                'channelRef' => '520DAN183111002',
                'billRef' => 'PE0123456789',
                'extraFields' => []
            ]);

        $this->displayResponse($statusCheckResponse);
    }

    protected function displayResponse($response)
    {
        $this->line('Status Code: ' . $response->status());
        $this->line('Response: ' . json_encode($response->json(), JSON_PRETTY_PRINT));
        $this->newLine();
    }
}
