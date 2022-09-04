<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->nullable()->comment('کاربری که این آگهی را درج کرده است');
            $table->string('title', 500)->nullable()->comment('عنوان فارسی آگهی');
            $table->string('en_title', 500)->nullable()->comment('عنوان لاتین آگهی');
            $table->text('description')->nullable()->comment('توضیح فارسی آگهی');
            $table->text('en_description')->nullable()->comment('توضیح لاتین آگهی');
            $table->string('meta_title', 500)->nullable()->comment('تعیین تگ متای عنوان');
            $table->string('meta_keywords', 500)->nullable()->comment('تعیین تگ متای کلمات کلیدی (با کاما از هم جدا می شوند)');
            $table->string('meta_description', 2000)->nullable()->comment('تعیین تگ متای توضیحات');
            $table->string('meta_author', 250)->nullable()->comment('تعیین تگ متای نویسنده');
            $table->integer('category_id')->nullable()->index()->comment('دسته بندی این آگهی');
            $table->string('video_link', 200)->nullable()->comment('لینک ویدئوی آگهی (اختیاری)');
            $table->string('phone', 50)->nullable()->comment('تلفن محل آگهی');
            $table->string('mobile', 50)->nullable()->comment('موبایل محل آگهی');
            $table->string('facebook', 50)->nullable()->comment('لینک فیسبوک');
            $table->string('instagram', 50)->nullable()->comment('لینک اینستاگرام');
            $table->string('twitter', 50)->nullable()->comment('لینک توییتر');
            $table->string('youtube', 50)->nullable()->comment('لینک یوتیوب');
            $table->string('email', 50)->nullable()->comment('ایمیل محل آگهی');
            $table->string('address', 2000)->nullable()->comment('آدرس فارسی محل آگهی');
            $table->string('en_address', 2000)->nullable()->comment('آدرس لاتین محل آگهی');
            $table->integer('city_id')->nullable()->index()->comment('تعین شهر محل آگهی');
            $table->string('postal_code', 250)->nullable()->comment('کد پستی محل آگهی');
            $table->integer('code')->nullable()->comment('کد آگهی');
            $table->double('lat')->nullable()->comment('عرض جغرافیایی محل آگهی');
            $table->double('long')->nullable()->comment('طول جغرافیایی محل آگهی');
            $table->timestamp('start_date')->nullable()->comment('زمان شروع آگهی');
            $table->timestamp('end_date')->nullable()->comment('زمان پایان آگهی');
            $table->timestamp('last_end_date')->nullable()->comment('آخرین زمان تمدید آگهی');
            $table->boolean('is_extended')->default(0)->comment('بررسی اینکه آیا آگهی تمدید شده است یا خیر');
            $table->boolean('is_immediate')->default(0)->comment('تعیین اینکه آیا آگهی از نوع "آگهی فوری" است یا خیر');
            $table->boolean('is_vip')->default(0)->comment('تعیین اینکه آیا آگهی از نوع "آگهی ویژه" است یا خیر');
            $table->boolean('is_top')->default(0)->comment('تعیین اینکه آیا آگهی از نوع "آگهی با اولویت بالا" است یا خیر');
            $table->decimal('paid_price', 12, 0)->default(0)->comment('هزینه آگهی');
            $table->boolean('payed_at')->default(0)->comment('تعیین اینکه آیا هزینه آگهی پرداخت شده است یا خیر');
            $table->boolean('is_free')->default(1)->comment('تعیین اینکه این آگهی رایگان است یا نیاز به پرداخت دارد');
            $table->integer('pay_id')->nullable()->index()->comment('تعیین جزییات پرداخت این آگهی');
            $table->integer('extended_time')->default(0)->comment('تعیین اینکه این آگهی چند بار تمدید شده است');
            $table->integer('published_admin_id')->nullable()->comment('تعیین اینکه کدام مدیر این آگهی را تایید کرده است');
            $table->timestamp('published_at')->nullable()->comment('تعیین اینکه چه زمانی آگهی تایید شده است');
            $table->integer('seen_admin_id')->nullable()->index()->comment('تعیین اینکه کدام ادمین این آگهی را ابتدا دیده است');
            $table->timestamp('seen_at')->nullable()->comment('تعیین اینکه چه زمانی دیدگاه دیده شده است');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `ads` comment 'جدول مربوط به آگهی ها'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
