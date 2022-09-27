<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoWalletWithdrawApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_wallet_withdraw_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('temp_withdraw_id');
            $table->bigInteger('wallet_id');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('co_wallet_withdraw_approvals');
    }
}
