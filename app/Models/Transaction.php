<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Wallet;

class Transaction extends Model
{
    protected $fillable = ['status_code', 'status_message','transaction_status', 'service_fee', 'credited_amount', 'maxRefundable_amount','payment_id', 'agreement_id', 'trx_iD', 'merchant_invoice','wallet_id', 'type', 'amount', 'description'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}