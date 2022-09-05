<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->comment('کاربری که امتیاز می دهد');
            $table->integer('ads_id')->index()->comment('آگهی مربوطه');
            $table->float('score')->comment('مقدار امتیاز که می تواند اعشاری و از 1 تا 5 باشد');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `scores` comment 'حدول مربوط به امتیاز کاربران به آگهی ها'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
