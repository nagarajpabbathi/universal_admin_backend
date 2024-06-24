<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('featured_image');
            $table->text('text_area');
            $table->string('category');
            $table->string('question');
            $table->string('answer1');
            $table->string('answer2');
            $table->text('correct_answer');
            $table->string('youtube_link')->nullable();
            $table->string('upload_video')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

