<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('کاربر مورد نظر');
            $table->integer('ads_id')->comment(' آگهی مورد نظر');
            $table->timestamps();

            $table->index(['user_id', 'ads_id']);
        });
        DB::statement("ALTER TABLE `bookmarks` comment 'جدول مربوط به ثبت آگهی های نشان شده برای کاربران'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
