<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount',13,8)->default(0);
            $table->bigInteger('sender_user_id');
            $table->bigInteger('receiver_user_id');
            $table->bigInteger('sender_wallet_id');
            $table->bigInteger('receiver_wallet_id');
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
        Schema::dropIfExists('coin_requests');
    }
}
