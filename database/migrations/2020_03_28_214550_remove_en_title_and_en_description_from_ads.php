<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEnTitleAndEnDescriptionFromAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['en_title', 'en_description']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('en_title', 500)->nullable()->comment('عنوان لاتین آگهی');
            $table->text('en_description')->nullable()->comment('توضیح لاتین آگهی');
            //
        });
    }
}
