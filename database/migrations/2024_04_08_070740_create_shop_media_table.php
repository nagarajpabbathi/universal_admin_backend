<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopMediaTable extends Migration
{
    public function up()
    {
        Schema::create('shop_media', function (Blueprint $table) {
            $table->id();

            $table->string('media_url')->unique();
            $table->enum('media_type', ['youtube_url', 'video', 'image' , 'image_url']);

            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');

            $table->string('section')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_media');
    }
}
