<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->nullable()->comment('کاربر مورد نظر در صورتی که در سامانه ورود کرده باشد');
            $table->string('name', 250)->nullable()->comment('نام ارایه دهنده دیدگاه');
            $table->string('email', 50)->nullable()->comment('ایمیل (اختیاری)');
            $table->string('mobile', 50)->nullable()->comment('موبایل (اختیاری)');
            $table->string('text', 3000)->comment('دیدگاه کاربر');
            $table->integer('parent_id')->nullable()->index()->comment('مشخص می کند که آیا این دیدگاه در پاسخ به دیدگاه دیگری ارائه شده است یا خیر');
            $table->integer('commentable_id')->index()->comment('تعیین شناسه فیلدی که برای آن دیدگاه ارائه شده است');
            $table->string('commentable_type', 50)->comment('تعیین جدولی که برای آن دیدگاه ارایه شده است: آگهی یا خبر');
            $table->integer('published_admin_id')->nullable()->comment('تعیین اینکه کدام مدیر این دیدگاه را تایید کرده است');
            $table->timestamp('published_at')->nullable()->comment('تعیین اینکه چه زمانی دیدگاه تایید شده است');
            $table->integer('seen_admin_id')->nullable()->index()->comment('تعیین اینکه کدام ادمین این دیدگاه را ابتدا دیده است');
            $table->timestamp('seen_at')->nullable()->comment('تعیین اینکه چه زمانی دیدگاه دیده شده است');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `comments` comment 'جدول مربوط به اطلاعات دیدگاه های کاربران در رابطه با خبر ها و آگهی ها : morph_many'");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
