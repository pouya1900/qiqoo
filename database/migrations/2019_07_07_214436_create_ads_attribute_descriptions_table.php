<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdsAttributeDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_attribute_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', '50')->nullable()->comment('عنوان فیلتر به فارسی');
            $table->string('field_name', '50')->nullable()->comment('نام فیلدی که داده کاربر در آن وارد می شود. (برای جلوگیری از تداخل با نام دیگر خصوصیات، با پیشوند att_ شروع شود)');
            $table->integer('unit_id')->index('unit_id_index')->nullable()->comment('تعیین واحد ویژگی');
            $table->integer('ads_attribute_description_value_type_id')->index('ads_attribute_description_value_type_id')->comment('نوع فیلتر را مشخص می کند که از نوع عددی است یا رشته و اینکه مقدار ورودی چگونه باید وارد شود');
            $table->integer('admin_id')->index()->comment('تعیین کاربر مدیری که این رکورد را ایجاد کرده است');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `ads_attribute_descriptions` comment 'حدول تعیین عنوان فیلتر : key'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_attribute_descriptions');
    }
}
