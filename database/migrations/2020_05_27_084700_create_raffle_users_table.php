<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_users', function (Blueprint $table) {
            $table->id();
            $table->string('openid', 50)->unique()->comment('中奖人微信openid');
            $table->string('nickname')->nullable(true)->comment('中奖人微信名');
            $table->string('avatar')->nullable(true)->comment('中奖人头像');
            $table->char('phone', 11)->nullable(true)->comment('中奖人手机号');
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
        Schema::dropIfExists('raffle_users');
    }
}
