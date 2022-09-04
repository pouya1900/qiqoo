<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 500)->nullable()->comment('عنوان فارسی خبر');
            $table->string('en_title', 500)->nullable()->comment('عنوان لاتین خبر');
            $table->string('short_description', 5000)->nullable()->comment('توضیح کوتاه فارسی خبر');
            $table->string('en_short_description', 5000)->nullable()->comment('توضیح کوتاه لاتین خبر');
            $table->string('meta_title', 500)->nullable()->comment('تعیین تگ متای عنوان');
            $table->string('meta_keywords', 500)->nullable()->comment('تعیین تگ متای کلمات کلیدی (با کاما از هم جدا می شوند)');
            $table->string('meta_description', 2000)->nullable()->comment('تعیین تگ متای توضیحات');
            $table->string('meta_author', 250)->nullable()->comment('تعیین تگ متای نویسنده');
            $table->integer('category_id')->nullable()->index()->comment('دسته بندی این خبر');
            $table->text('text')->nullable()->comment('متن اصلی فارسی خبر');
            $table->text('en_text')->nullable()->comment('متن اصلی لاتین خبر');
            $table->integer('admin_id')->index()->comment('تعیین کاربر مدیری که این خبر را ایجاد کرده است');
            $table->timestamp('published_at')->nullable()->comment('تعیین اینکه چه زمانی خبر منتشر شده است');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `blogs` comment 'جدول مربوط به ثبت اطلاعات خبر ها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
