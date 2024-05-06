<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryShopTable extends Migration
{
    public function up()
    {
        Schema::create('category_shop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');

            // Make sure a shop is not repeated within the same category
            $table->unique(['category_id', 'shop_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_shop');
    }
}
