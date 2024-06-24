<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLuckydrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luckydraws', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('logo_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('text_area');
            $table->string('category');
            $table->decimal('prize_amount', 10, 2);
            $table->decimal('entry_amount', 10, 2);
            $table->integer('max_tickets_per_submission')->default(1);
            $table->integer('lucky_number_digits')->default(6);
            $table->boolean('allow_upload_receipt')->default(false);
            $table->dateTime('expired_date');
            $table->dateTime('draw_date');
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
        Schema::dropIfExists('luckydraws');
    }
}
