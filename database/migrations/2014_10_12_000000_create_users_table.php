<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('شناسه کاربر');
            $table->string('first_name')->nullable()->comment('نام کاربر');
            $table->string('last_name')->nullable()->comment('نام خانوادگی');
            $table->string('username')->nullable()->unique()->comment('نام کاربری');
            $table->unsignedBigInteger('mobile')->nullable()->unique()->comment('شماره موبایل کاربر');
            $table->string('email')->nullable()->unique()->comment('ایمیل کاربر');
            $table->string('password')->nullable()->comment('رمز عبور کاربر');
            $table->string('token')->nullable()->comment('jwt-token کاربر');
            $table->integer('role_id')->index()->nullable()->comment('نوع کاربر');
            $table->integer('phone_code')->nullable()->comment('کد تلفن (تعیین کشور کاربر)');
            $table->string('invitation_code')->nullable()->comment('بررسی اینکه آیا دعوت شده استوو توسط چه کسی دعوت شده است.');
            $table->integer('invited_by')->default(false)->comment('بررسی اینکه آیا دعوت شده است');
            $table->timestamp('mobile_verified_at')->nullable()->comment('زمان تایید شدن شماره کاربر');
            $table->timestamp('email_verified_at')->nullable()->comment('زمان تایید شدن ایمیل کاربر');
            $table->timestamps();
            $table->softDeletes()->comment('soft remove موجودیت');
        });
        DB::statement("ALTER TABLE `users` comment 'اطلاعات همه ی انواع کاربران'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
