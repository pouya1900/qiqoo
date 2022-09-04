<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250)->nullable()->comment('نام کاربر ارسال کننده پیام (اختیاری)');
            $table->string('mobile', 50)->nullable()->comment('شماره موبایل کاربر ارسال کننده پیام (اختیاری)');
            $table->string('email', 50)->nullable()->comment('ایمیل کاربر ارسال کننده پیام (اختیاری)');
            $table->string('title', 500)->comment('عنوان پیام');
            $table->text('text')->comment('متن اصلی پیام');
            $table->integer('seen_admin_id')->nullable()->comment('تعیین اینکه کدام ادمین این پیام پشتیبانی را ابتدا دیده است');
            $table->timestamp('seen_at')->nullable()->comment('تعیین اینکه چه زمانی پیام پشتیبانی دیده شده است');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `supports` comment 'جدول مربوط به اطلاعات پیام های پشتیبانی'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supports');
    }
}
