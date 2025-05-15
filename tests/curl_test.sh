#!/bin/bash

# Base URL - replace with your actual domain
BASE_URL="http://localhost:8000/api"

# Common headers
HEADERS=(
    "Content-Type: application/json"
    "Accept: application/json"
    "Authorization: Bearer YOUR_JWT_TOKEN_HERE"
    "Digital-Signature: YOUR_DIGITAL_SIGNATURE_HERE"
    "Timestamp: $(date -u +"%Y-%m-%dT%H:%M:%S.000Z")"
)

# 1. Test Inquiry API
echo "Testing Inquiry API..."
curl -X POST "${BASE_URL}/bills-payments-api/api/v1/inquiry" \
    -H "${HEADERS[0]}" \
    -H "${HEADERS[1]}" \
    -H "${HEADERS[2]}" \
    -H "${HEADERS[3]}" \
    -H "${HEADERS[4]}" \
    -d '{
        "channelId": "CBP1010101",
        "spCode": "BPE0001000BC",
        "requestType": "inquiry",
        "timestamp": "'$(date -u +"%Y-%m-%dT%H:%M:%S.000Z")'",
        "userId": "USER101",
        "branchCode": "015",
        "channelRef": "520DAN18311100298",
        "billRef": "PE0123456789",
        "extraFields": {}
    }'

echo -e "\n\n"

# 2. Test Payment API (Async)
echo "Testing Payment API (Async)..."
curl -X POST "${BASE_URL}/bills-payments-api/api/v1/payment" \
    -H "${HEADERS[0]}" \
    -H "${HEADERS[1]}" \
    -H "${HEADERS[2]}" \
    -H "${HEADERS[3]}" \
    -H "${HEADERS[4]}" \
    -d '{
        "channelId": "CBP1010101",
        "spCode": "BPE0001000BC",
        "requestType": "payment",
        "approach": "async",
        "callbackUrl": "https://your-domain.com/api/callback",
        "timestamp": "'$(date -u +"%Y-%m-%dT%H:%M:%S.000Z")'",
        "userId": "USER101",
        "branchCode": "015",
        "billRef": "PE0123456789",
        "channelRef": "520DAN183111002",
        "amount": "25000",
        "creditAccount": "0122****1486",
        "creditCurrency": "TZS",
        "paymentType": "ACCOUNT",
        "channelCode": "APP",
        "payerName": "John Doe",
        "payerPhone": "255715000000",
        "payerEmail": "john.doe@example.com",
        "narration": "Test Bills Payment",
        "extraFields": {}
    }'

echo -e "\n\n"

# 3. Test Payment API (Sync)
echo "Testing Payment API (Sync)..."
curl -X POST "${BASE_URL}/bills-payments-api/api/v1/payment" \
    -H "${HEADERS[0]}" \
    -H "${HEADERS[1]}" \
    -H "${HEADERS[2]}" \
    -H "${HEADERS[3]}" \
    -H "${HEADERS[4]}" \
    -d '{
        "channelId": "CBP1010101",
        "spCode": "BPE0001000BC",
        "requestType": "payment",
        "approach": "sync",
        "callbackUrl": "",
        "timestamp": "'$(date -u +"%Y-%m-%dT%H:%M:%S.000Z")'",
        "userId": "USER101",
        "branchCode": "015",
        "billRef": "PE0123456789",
        "channelRef": "520DAN183111003",
        "amount": "25000",
        "creditAccount": "0122****1486",
        "creditCurrency": "TZS",
        "paymentType": "ACCOUNT",
        "channelCode": "APP",
        "payerName": "John Doe",
        "payerPhone": "255715000000",
        "payerEmail": "john.doe@example.com",
        "narration": "Test Bills Payment",
        "extraFields": {}
    }'

echo -e "\n\n"

# 4. Test Status Check API
echo "Testing Status Check API..."
curl -X POST "${BASE_URL}/bills-payments-api/api/v1/status-check" \
    -H "${HEADERS[0]}" \
    -H "${HEADERS[1]}" \
    -H "${HEADERS[2]}" \
    -H "${HEADERS[3]}" \
    -H "${HEADERS[4]}" \
    -d '{
        "channelId": "CBP1010101",
        "spCode": "BPE0001000BC",
        "requestType": "statusCheck",
        "timestamp": "'$(date -u +"%Y-%m-%dT%H:%M:%S.000Z")'",
        "channelRef": "520DAN183111002",
        "billRef": "PE0123456789",
        "extraFields": {}
    }'

echo -e "\n"
