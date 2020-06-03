<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleJackpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_jackpots', function (Blueprint $table) {
            $table->id();
            $table->integer('raffle_state_id')->comment('奖金档次id');
            $table->tinyInteger('use_status')->default(0)->comment('0、未使用 1、已使用');
            $table->integer('raffle_draw_list_id')->nullable(true)->comment('二维码url id');
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
        Schema::dropIfExists('raffle_jackpots');
    }
}
