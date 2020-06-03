<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsTransmitToRaffleDrawListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raffle_draw_lists', function (Blueprint $table) {
            $table->tinyInteger('is_transmit')->default(0)->after('is_win')->comment('0、未转发 1、已转发');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raffle_draw_lists', function (Blueprint $table) {
            //
        });
    }
}
