<?php

class Transaction extends Model
{
    protected $fillable = ['wallet_id', 'type', 'amount', 'description'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}