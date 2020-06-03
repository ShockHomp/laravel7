<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaffleDrawListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_draw_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('url_code_log_id')->comment('url二维码批量添加的log_id');
            $table->string('openid')->nullable(true)->comment('中奖人微信openid');
            $table->string('nickname')->nullable(true)->comment('中奖人微信名');
            $table->string('avatar')->nullable(true)->comment('中奖人头像');
            $table->char('phone', 11)->nullable(true)->comment('中奖人手机号');
            $table->string('amount')->nullable(true)->comment('中奖金额');
            $table->timestamp('win_time')->nullable(true)->comment('中奖时间');
            $table->integer('raffle_state_id')->nullable(true)->comment('奖金档次id');
            $table->tinyInteger('is_grand')->default(0)->comment('0、不是大奖 1、大奖');
            $table->string('grand_prize_picture')->nullable(true)->comment('大奖上传图片');
            $table->decimal('lat', 12, 8)->nullable(true)->comment('中奖人GPS 精度');
            $table->decimal('lng', 12, 8)->nullable(true)->comment('中奖人GPS 纬度');
            $table->integer('distributor_id')->nullable(true)->comment('奖金来源经销商id');
            $table->tinyInteger('is_win')->default(0)->comment('0、未中奖 1、中奖');
            $table->tinyInteger('is_release')->default(0)->comment('0、未发放 1、已发放');
            $table->timestamp('release_time')->nullable(true)->comment('发放时间');
            $table->string('partner_trade_no')->nullable(true)->comment('商户订单号');
            $table->string('payment_no')->nullable(true)->comment('微信付款单号');
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
        Schema::dropIfExists('raffle_draw_lists');
    }
}
