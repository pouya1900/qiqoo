<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateUserloginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logins', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('شناسه رکورد');
            $table->integer('user_id')->index()->comment('تعیین کاربر مورد نظر');
            $table->string('uuid')->comment('تعیین شناسه منحصر دستگاه');
            $table->string('platform')->comment('پلتفرم مورد استفاده (موبایل یا وب)');
            $table->string('model')->comment('مدل دستگاه مورد استفاده کاربر');
            $table->string('os')->comment('سیستم عامل کاربر');
            $table->boolean('is_active')->default(1)->comment('تعیین اینکه آیا اکنون لاگین کاربر معتبر است یا خیر');
            $table->timestamp('login_at')->nullable()->comment('زمان لاگین کاربر');
            $table->timestamp('logout_at')->nullable()->comment('زمان خروج کاربر');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `user_logins` comment 'جدول مربوط به ثبت ورودهای کاربران'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logins');
    }
}
