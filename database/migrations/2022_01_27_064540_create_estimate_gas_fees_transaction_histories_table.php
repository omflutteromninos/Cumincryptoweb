<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateGasFeesTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_gas_fees_transaction_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_code', 180)->unique()->nullable();
            $table->bigInteger('deposit_id');
            $table->bigInteger('wallet_id');
            $table->decimal('amount',19,8)->default(0);
            $table->decimal('fees',19,8)->default(0);
            $table->string('coin_type')->default('BTC');
            $table->string('admin_address');
            $table->string('user_address');
            $table->string('transaction_hash');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_gas_fees_transaction_histories');
    }
}
