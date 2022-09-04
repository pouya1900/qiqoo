<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activations', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('شناسه رکورد');
            $table->unsignedBigInteger('code')->index()->comment('کد فعالسازی کاربر');
            $table->unsignedBigInteger('mobile')->nullable()->index()->comment('شماره موبایل ارسال otp');
            $table->integer('retry_time')->default(0)->comment('تعداد دفعات اشتباه وارد کردن otp');
            $table->timestamp('retry_at')->nullable()->comment('آخرین زمان وارد کردن  otp');
            $table->timestamp('completed_at')->nullable()->comment('زمان کامل شدن ورود با کد فعالسازی');
            $table->timestamp('expired_at')->nullable()->comment('تعیین انقضا otp');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `activations` comment 'جدول مربوط به کدهای فعالسازی کاربران'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
    }
}
