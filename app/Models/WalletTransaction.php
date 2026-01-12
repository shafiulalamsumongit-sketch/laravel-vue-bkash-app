<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Wallet;



class WalletTransaction extends Model
{
    protected $fillable = ['wallet_id', 'type', 'amount', 'reference'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}


