<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAtCoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->string('coin_icon', 50)->nullable()->after('is_sell');
            $table->boolean('is_base')->default(1)->after('is_sell');
            $table->boolean('is_currency')->default(0)->after('is_sell');
            $table->boolean('is_primary')->nullable()->unique()->after('is_sell');
            $table->boolean('is_wallet')->default(0)->after('is_sell');
            $table->boolean('is_transferable')->default(0)->after('is_sell');
            $table->boolean('is_virtual_amount')->default(0)->after('is_sell');
            $table->tinyInteger('trade_status')->default(1)->after('is_sell');
            $table->string('sign')->nullable()->collation('utf8_unicode_ci')->after('is_sell');
            $table->decimal('minimum_buy_amount', 19.0, 8.0)->default(0.0000001)->after('is_sell');
            $table->decimal('minimum_sell_amount', 19.0, 8.0)->default(0.0000001)->after('is_sell');
            $table->decimal('minimum_withdrawal', 19, 8)->default(0.0000001)->after('is_sell');
            $table->decimal('maximum_withdrawal', 19, 8)->default(	99999999.0)->after('is_sell');
            $table->decimal('withdrawal_fees', 19, 8)->default(	0)->after('is_sell');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            //
        });
    }
}
