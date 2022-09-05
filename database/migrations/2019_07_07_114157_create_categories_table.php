<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->index()->comment('تعیین کاربر مدیری که این دسته بندی را ایجاد کرده است');
            $table->string('title', 500)->nullable()->comment('عنوان فارسی دسته بندی');
            $table->string('en_title', 500)->nullable()->comment('عنوان لاتین دسته بندی');
            $table->string('description', 3000)->nullable()->comment('توضیح فارسی دسته بندی');
            $table->string('en_description', 3000)->nullable()->comment('توضیح لاتین دسته بندی');
            $table->integer('parent_id')->nullable()->index()->comment('اگر زیر دسته باشد شناسه والد اینجا قرار می گیرد');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `categories` comment 'جدول مربوط به ثبت دسته بندی ها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
