<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->nullable()->comment('کاربر مورد نظر در صورتی که در سامانه ورود کرده باشد');
            $table->string('name', 250)->nullable()->comment('نام گزارش دهنده');
            $table->string('email', 50)->nullable()->comment('ایمیل گزارش دهنده (اختیاری)');
            $table->string('mobile', 50)->nullable()->comment('موبایل گزارش دهنده (اختیاری)');
            $table->string('text', 3000)->comment('گزارش کاربر');
            $table->integer('ads_id')->index()->comment('تعیین شناسه آگهی');
            $table->integer('report_type_id')->index()->comment('نوع گزارش با توجه به درجه حساسیت آن که به جدول انواع گزارش متصل می شود');
            $table->integer('seen_admin_id')->nullable()->comment('تعیین اینکه کدام ادمین این گزارش را ابتدا دیده است');
            $table->timestamp('seen_at')->nullable()->comment('تعیین اینکه چه زمانی گزارش دیده شده است');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `reports` comment 'جدول مربوط به گزارشات کاربران از آگهی و خبر ها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
