<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('shop_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_sections');
    }
}
