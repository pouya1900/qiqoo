<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 250)->nullable()->comment('عنوان فارسی کشور');
            $table->string('en_title', 250)->nullable()->comment('عنوان لاتین کشور');
            $table->string('iso_code', 50)->nullable()->comment('کد کشور');
            $table->string('iso_code3', 50)->nullable()->comment('کد کشور (استاندارد سه حرفی)');
            $table->smallInteger('phone_code')->comment('کد تلفن کشور');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `countries` comment 'جدول مربوط به اطلاعات کشورها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
