<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'sp_code',
        'request_type',
        'timestamp',
        'user_id',
        'branch_code',
        'channel_ref',
        'bill_ref',
        'amount',
        'credit_account',
        'credit_currency',
        'payment_type',
        'channel_code',
        'payer_name',
        'payer_phone',
        'payer_email',
        'narration',
        'gateway_ref',
        'biller_receipt',
        'transaction_time',
        'accounting_status',
        'biller_notified',
        'status',
        'extra_fields'
    ];

    protected $casts = [
        'extra_fields' => 'array',
        'timestamp' => 'datetime',
        'transaction_time' => 'datetime'
    ];
}
