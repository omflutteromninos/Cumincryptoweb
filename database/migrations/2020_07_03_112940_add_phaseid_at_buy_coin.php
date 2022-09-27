<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhaseidAtBuyCoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_coin_histories', function (Blueprint $table) {
            $table->bigInteger('phase_id')->nullable()->after('coin_type');
            $table->integer('referral_level')->nullable()->after('coin_type');
            $table->decimal('fees', 19, 8)->default(0)->after('coin_type');
            $table->decimal('bonus', 19, 8)->default(0)->after('coin_type');
            $table->decimal('referral_bonus', 19, 8)->default(0)->after('coin_type');
            $table->decimal('requested_amount', 19, 8)->default(0)->after('coin_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_coin_histories', function (Blueprint $table) {
            $table->dropColumn('phase_id');
            $table->dropColumn('referral_level');
            $table->dropColumn('fees');
            $table->dropColumn('bonus');
            $table->dropColumn('requested_amount');
            $table->dropColumn('referral_bonus');
        });
    }
}
