<?php

namespace App\Models;

class WalletTransaction extends Model
{
    protected $fillable = ['wallet_id', 'type', 'amount', 'reference'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}


