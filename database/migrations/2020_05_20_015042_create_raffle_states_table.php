<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_states', function (Blueprint $table) {
            $table->id();
            $table->string('grade')->nullable(true)->comment('奖金档次');
            $table->string('win_chance')->nullable(true)->comment('中奖几率');
            $table->integer('prize_number')->comment('分配个数');
            $table->tinyInteger('is_grand')->default(0)->comment('0、不是大奖 1、大奖');
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
        Schema::dropIfExists('raffle_states');
    }
}
