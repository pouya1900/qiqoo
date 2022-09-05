<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viewable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->nullable()->comment('کاربر بازدید کننده (در صورت ورود)');
            $table->string('uuid')->nullable()->comment('تعیین شناسه منحصر دستگاه');
            $table->string('platform')->nullable()->comment('پلتفرم مورد استفاده (موبایل یا وب)');
            $table->string('model')->nullable()->comment('مدل دستگاه مورد استفاده کاربر');
            $table->string('os')->nullable()->comment('سیستم عامل کاربر');
            $table->integer('viewable_id')->index()->comment('تعیین شناسه فیلدی که دیده شده است');
            $table->string('viewable_type')->comment('تعیین جدولی که دیده شده است: آگهی یا خبر');
            $table->timestamps();
            $table->softDeletes();
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `viewable` comment 'جدول میانی مربوط به ثبت بازدید کاربران از آگهی ها و خبر ها : morph many'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viewable');
    }
}
