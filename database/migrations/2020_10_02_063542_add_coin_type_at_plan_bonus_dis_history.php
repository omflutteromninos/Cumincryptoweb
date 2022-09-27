<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoinTypeAtPlanBonusDisHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_bonus_distribution_histories', function (Blueprint $table) {
            $table->string('bonus_coin_type')->default(DEFAULT_COIN_TYPE)->after('bonus_type');
            $table->decimal('bonus_amount_btc',13,8)->default(0)->after('bonus_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_bonus_distribution_histories', function (Blueprint $table) {
            $table->dropColumn('bonus_coin_type');
            $table->dropColumn('bonus_amount_btc');
        });
    }
}
