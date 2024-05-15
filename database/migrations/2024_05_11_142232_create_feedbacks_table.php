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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('positivity');
            $table->text('comment_description')->nullable();
            $table->string('image')->nullable();
            $table->integer('amount_purchased')->nullable();
            $table->string('served_by')->nullable();
            $table->integer('rating_count')->nullable();
            $table->timestamps();

             // Polymorphic relationship
             $table->unsignedBigInteger('related_entity_id');
             $table->string('related_entity_type');

             // User relationship
             $table->unsignedBigInteger('user_id');
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
