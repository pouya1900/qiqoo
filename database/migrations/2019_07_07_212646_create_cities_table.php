<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('country_id')->nullable()->index()->comment('تعیین کشور این شهر');
            $table->string('title', 250)->nullable()->comment('عنوان فارسی شهر');
            $table->string('en_title', 250)->nullable()->comment('عنوان لاتین شهر');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `cities` comment 'جدول مربوط به شهرها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
