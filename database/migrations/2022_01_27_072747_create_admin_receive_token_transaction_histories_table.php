<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminReceiveTokenTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_receive_token_transaction_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('deposit_id');
            $table->string('unique_code', 180)->unique()->nullable();
            $table->decimal('amount',19,8)->default(0);
            $table->decimal('fees',19,8)->default(0);
            $table->string('to_address');
            $table->string('from_address');
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
        Schema::dropIfExists('admin_receive_token_transaction_histories');
    }
}
