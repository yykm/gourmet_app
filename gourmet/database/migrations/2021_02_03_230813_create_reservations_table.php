<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            // 主キー
            $table->increments('id');

            // 外部キー
            $table->string('shop_id');
            $table->unsignedInteger('user_id');

            // その他
            $table->string('name', 80)->comment('予約者名');
            ;
            $table->string('kana', 80)->comment('かな');
            ;
            $table->date('date')->comment('予約日');
            $table->time('time')->comment('予約時間');
            $table->tinyInteger('number')->unsigned()->comment('人数');
            $table->string('email')->comment('メールアドレス');
            $table->string('phone_num', 20)->comment('電話番号');
            $table->string('purpose', 20)->nullable()->comment('利用目的');
            $table->text('request')->nullable()->comment('ご要望・ご意見');
            $table->timestamps();

            // 関連
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
