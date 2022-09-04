<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->integer('user_id')->index()->comment('کاربر این پروفایل');
            $table->string('image', 250)->nullable()->comment('تصویر پروفایل (اختیاری)');
            $table->boolean('female')->default(0)->comment('1 برای خانم و 0 برای آقا');
            $table->double('lat')->nullable()->comment('عرض جغرافیایی کاربر ثبت نام شده 0اختیاری)');
            $table->double('long')->nullable()->comment('طول جغرافیایی این کاربر (اختیاری)');
            $table->integer('city_id')->index()->nullable()->comment('شهر این کاربر (اختیاری)');
            $table->timestamp('birthday')->nullable()->comment('سال تولد کاربر (اختیاری)');
            $table->string('address', 2000)->nullable()->comment('آدرس کاربر (اختیاری)');
            $table->string('company', 500)->nullable()->comment('نام شرکت کاربر (اختیاری)');
            $table->string('en_company', 500)->nullable()->comment('نام شرکت کاربر به انگلیسی (اختیاری)');
            $table->string('about', 3000)->nullable()->comment('درباره کاربر (اختیاری)');
            $table->string('en_about', 3000)->nullable()->comment('درباره کاربر به انگلیسی (اختیاری)');
            $table->string('specialist', 3000)->nullable()->comment('تخصص کاربر (اختیاری)');
            $table->string('en_specialist', 3000)->nullable()->comment('تخصص کاربر به انگلیسی (اختیاری)');
            $table->string('facebook', 50)->nullable()->comment('قیسبوک (اختیاری)');
            $table->string('twitter', 50)->nullable()->comment('توییتر (اختیاری)');
            $table->string('linkedin', 50)->nullable()->comment('لینکداین (اختیاری)');
            $table->string('instagram', 50)->nullable()->comment('اینستاگرام (اختیاری)');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `profiles` comment 'جدول مربوط به پروفایل کاربران'");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
