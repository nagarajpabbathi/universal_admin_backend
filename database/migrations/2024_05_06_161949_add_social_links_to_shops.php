<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinksToShops extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            // Add more social links columns if needed
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('instagram');
            // Drop additional social links columns if added
        });
    }
}
