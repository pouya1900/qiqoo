<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ads_attribute_description_id')->index()->comment('عنوان این مقدار را مشخص میکند');
            $table->integer('ads_id')->index()->comment('تعیین آگهی');
            $table->bigInteger('integer_value')->nullable()->comment('در صورتی که فیلتر از نوع عددی باشد این فیلد باید مقداردهی شود');
            $table->string('string_value', 1000)->nullable()->comment('در صورتی که فیلتر از نوع رشته ای باشد این فیلد باید مقداردهی شود');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `ads_attributes` comment 'جدول تعیین مقدار فیلدر : value'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_attributes');
    }
}
