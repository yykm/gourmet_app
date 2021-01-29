<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            // 主キー
            $table->string('id')->primary();

            // 外部キー
            $table->unsignedInteger('user_id');
            $table->string('shop_id');
            $table->unsignedInteger('comment_id')->nullable()->unique();

            // その他
            $table->string('filename');
            $table->timestamps();

            // 関連
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
