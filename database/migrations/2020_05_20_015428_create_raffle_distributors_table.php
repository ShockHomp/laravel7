<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_distributors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(true)->comment('经销商名称');
            $table->string('reward')->nullable(true)->comment('存入奖金');
            $table->json('win_location')->nullable(true)->comment('中奖范围标点');
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
        Schema::dropIfExists('raffle_distributors');
    }
}
