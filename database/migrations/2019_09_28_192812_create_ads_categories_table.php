<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->index()->comment('تعیین کاربر مدیری که این دسته بندی را ایجاد کرده است');
            $table->string('title', 500)->nullable()->comment('عنوان فارسی دسته بندی');
            $table->string('en_title', 500)->nullable()->comment('عنوان لاتین دسته بندی');
            $table->string('description', 3000)->nullable()->comment('توضیح فارسی دسته بندی');
            $table->string('en_description', 3000)->nullable()->comment('توضیح لاتین دسته بندی');
            $table->integer('parent_id')->nullable()->index()->comment('اگر زیر دسته باشد شناسه والد اینجا قرار می گیرد');
            $table->boolean('show_in_home')->default(0)->comment('آیا در صفحه اصلی نمایش داده شود');
            $table->integer('order')->nullable()->comment('ترتیب نمایش در صفحه اصلی');
            $table->boolean('show_in_categories')->default(0)->comment('آیا در صفحه دسته بندی ها نمایش داده شود');
            $table->boolean('is_favorite')->default(0)->comment('اگر دسته بندی موردعلاقه باشد');
            $table->boolean('is_paid')->default(0)->comment('اگر پولی باشد');
            $table->decimal('price', 8, 0)->nullable()->comment('قیمت آگهی در این گروه');
            $table->softDeletes();
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
        Schema::dropIfExists('ads_categories');
    }
}
