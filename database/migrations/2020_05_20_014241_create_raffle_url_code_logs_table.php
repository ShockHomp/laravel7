<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleUrlCodeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_url_code_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->comment('生成二维码数量');
            $table->timestamp('start_time')->nullable(true)->comment('启用时间');
            $table->timestamp('end_time')->nullable(true)->comment('终止时间');
            $table->integer('user_id')->comment('生成账号');
            $table->tinyInteger('status')->default(0)->comment('0、未启用 1、启用');
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
        Schema::dropIfExists('raffle_url_code_logs');
    }
}
