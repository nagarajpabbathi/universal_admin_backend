<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('featured_image')->nullable();
            $table->decimal('bonus_amount', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);;
            $table->longText('description');
            $table->longText('question');
            $table->text('answer1');
            $table->text('answer2');
            $table->text('correct_answer');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_button_allowed')->default(false);;
            $table->string('button_label')->nullable();
            $table->string('button_link')->nullable();

            // // Category relationship
            // $table->unsignedBigInteger('category_id');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // Polymorphic relationship
            $table->unsignedBigInteger('related_entity_id');
            $table->string('related_entity_type');

            // User relationship
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
