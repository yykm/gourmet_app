<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            // 主キー
            $table->string('id')->primary();

            // その他
            $table->string('name', 80)->nullable()->comment('店舗名');
            ;
            $table->string('kana', 80)->nullable()->comment('店舗名かな');
            ;
            $table->string('image')->nullable()->comment('店舗画像URL');
            $table->string('address', 100)->nullable()->comment('店舗住所');
            $table->string('access')->nullable()->comment('アクセス');
            $table->text('catch', 100)->nullable()->comment('お店キャッチ');
            $table->string('open', 150)->nullable()->comment('営業時間');
            $table->string('category', 50)->nullable()->comment('お店のジャンル');
            $table->string('average', 80)->nullable()->comment('平均予算');
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
        Schema::dropIfExists('shops');
    }
}
