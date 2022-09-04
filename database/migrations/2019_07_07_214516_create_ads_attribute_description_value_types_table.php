<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdsAttributeDescriptionValueTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_attribute_description_value_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250)->comment('نوع مقدار فیلد فیلتر را مشخص می کند که معمولا میتواند string- number- boolean  باشد');
            $table->string('title', 250)->comment('عنوان نوع که در زمان ایجاد ویژگی نمایش داده می شود');
            $table->string('input_type',250)->comment('نوع وارد کردن مقدار را تعیین می کند که می تواند عددی، رشته یا بصورت رشته انتخابی (select) باشد. مانند: typing, selecting');
            $table->integer('admin_id')->index()->comment('تعیین کاربر مدیری که این رکورد را ایجاد کرده است');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `ads_attribute_description_value_types` comment 'جدول تعیین نوع فیلد عنوان فیلتر و نحوه ورود مقادیر ورودی و نحوه انتخاب د زمان  اعمال فیلتر'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_attribute_description_value_types');
    }
}
