<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcoPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ico_phases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phase_name')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->decimal('fees', 19, 8)->default(0);
            $table->decimal('rate', 19, 8)->default(0);
            $table->decimal('amount', 19, 8)->default(0);
            $table->decimal('bonus', 19, 8)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('affiliation_level')->nullable();
            $table->decimal('affiliation_percentage', 19, 8)->default(0);
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
        Schema::dropIfExists('ico_phases');
    }
}
