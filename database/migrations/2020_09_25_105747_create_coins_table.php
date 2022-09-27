<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type',20)->unique();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_withdrawal')->default(1);
            $table->tinyInteger('is_deposit')->default(1);
            $table->tinyInteger('is_buy')->default(1);
            $table->tinyInteger('is_sell')->default(1);
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
        Schema::dropIfExists('coins');
    }
}
