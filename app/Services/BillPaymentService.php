<?php

namespace App\Services;

use App\Models\BillPayment;
use Illuminate\Support\Str;

class BillPaymentService
{
    /**
     * Process a new bill payment
     */
    public function processPayment(array $data): BillPayment
    {
        // Generate unique references
        $data['gateway_ref'] = 'PE' . time() . Str::random(4);
        $data['biller_receipt'] = 'RCPT' . time() . Str::random(4);
        $data['transaction_time'] = now();
        $data['status'] = 'pending';
        $data['accounting_status'] = 'pending';
        $data['biller_notified'] = 'pending';

        // Create payment record
        $payment = BillPayment::create($data);

        // TODO: Add your business logic here
        // For example:
        // - Update customer's bill status
        // - Send notifications
        // - Update accounting records
        // - etc.

        return $payment;
    }

    /**
     * Get bill details
     */
    public function getBillDetails(string $billRef): array
    {
        // TODO: Implement your bill details retrieval logic
        // This is a sample response - replace with actual data from your system
        return [
            'billRef' => $billRef,
            'serviceName' => 'Sample Service',
            'description' => 'Sample Bill Description',
            'billCreatedAt' => now()->format('Y-m-d\TH:i:s'),
            'totalAmount' => '30000',
            'balance' => '30000',
            'phoneNumber' => '255715000000',
            'email' => 'customer@example.com',
            'billedName' => 'John Doe',
            'currency' => 'TZS',
            'paymentMode' => 'exact',
            'expiryDate' => now()->addDays(7)->format('Ymd\THis'),
            'creditAccount' => '0122****1486',
            'creditCurrency' => 'TZS',
            'extraFields' => []
        ];
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $channelRef, string $billRef): array
    {
        $payment = BillPayment::where('channel_ref', $channelRef)
            ->where('bill_ref', $billRef)
            ->first();

        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        return [
            'billRef' => $payment->bill_ref,
            'gatewayRef' => $payment->gateway_ref,
            'amount' => $payment->amount,
            'currency' => $payment->credit_currency,
            'transactionTime' => $payment->transaction_time->format('Ymd\THis'),
            'billerReceipt' => $payment->biller_receipt,
            'remarks' => 'Successfully received',
            'accountingStatus' => $payment->accounting_status,
            'billerNotified' => $payment->biller_notified,
            'extraFields' => $payment->extra_fields ?? []
        ];
    }
}
