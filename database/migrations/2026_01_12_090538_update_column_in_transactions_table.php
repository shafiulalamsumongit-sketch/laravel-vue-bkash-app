<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_id', 255)->unique();
            $table->string('agreement_id', 255)->unique();
            $table->string('trx_iD', 255);
            $table->string('merchant_invoice', 255);
            $table->string('transaction_status', 255);
            $table->string('service_fee', 255);
            $table->string('credited_amount', 255);
            $table->string('maxRefundable_amount', 255);
            $table->string('status_code', 255);
            $table->string('status_message', 255);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
