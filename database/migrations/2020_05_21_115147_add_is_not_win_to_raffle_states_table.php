<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNotWinToRaffleStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raffle_states', function (Blueprint $table) {
            $table->tinyInteger('is_not_win')->default(0)->after('is_grand')->comment('0、奖金 1、谢谢惠顾');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raffle_states', function (Blueprint $table) {
            //
        });
    }
}
