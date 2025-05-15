<?php

// Base URL - replace with your actual domain
$baseUrl = 'http://localhost:8000/api';

// Common headers
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer YOUR_JWT_TOKEN_HERE',
    'Digital-Signature: YOUR_DIGITAL_SIGNATURE_HERE',
    'Timestamp: ' . gmdate('Y-m-d\TH:i:s.000\Z')
];

// Function to make API calls
function makeApiCall($url, $headers, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch) . "\n";
    }

    curl_close($ch);

    echo "HTTP Status Code: " . $httpCode . "\n";
    echo "Response: " . $response . "\n\n";
}

// 1. Test Inquiry API
echo "Testing Inquiry API...\n";
$inquiryData = [
    'channelId' => 'CBP1010101',
    'spCode' => 'BPE0001000BC',
    'requestType' => 'inquiry',
    'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
    'userId' => 'USER101',
    'branchCode' => '015',
    'channelRef' => '520DAN18311100298',
    'billRef' => 'PE0123456789',
    'extraFields' => []
];
makeApiCall($baseUrl . '/bills-payments-api/api/v1/inquiry', $headers, $inquiryData);

// 2. Test Payment API (Async)
echo "Testing Payment API (Async)...\n";
$asyncPaymentData = [
    'channelId' => 'CBP1010101',
    'spCode' => 'BPE0001000BC',
    'requestType' => 'payment',
    'approach' => 'async',
    'callbackUrl' => 'https://your-domain.com/api/callback',
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
];
makeApiCall($baseUrl . '/bills-payments-api/api/v1/payment', $headers, $asyncPaymentData);

// 3. Test Payment API (Sync)
echo "Testing Payment API (Sync)...\n";
$syncPaymentData = [
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
];
makeApiCall($baseUrl . '/bills-payments-api/api/v1/payment', $headers, $syncPaymentData);

// 4. Test Status Check API
echo "Testing Status Check API...\n";
$statusCheckData = [
    'channelId' => 'CBP1010101',
    'spCode' => 'BPE0001000BC',
    'requestType' => 'statusCheck',
    'timestamp' => gmdate('Y-m-d\TH:i:s.000\Z'),
    'channelRef' => '520DAN183111002',
    'billRef' => 'PE0123456789',
    'extraFields' => []
];
makeApiCall($baseUrl . '/bills-payments-api/api/v1/status-check', $headers, $statusCheckData);
