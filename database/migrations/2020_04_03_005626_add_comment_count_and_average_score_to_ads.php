<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentCountAndAverageScoreToAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->float('average_score')->default(0)->after('description')->comment('میانگین امتیاز وارد شده برای آگهی');
            $table->integer('comment_count')->default(0)->after('average_score')->comment('تعداد کامنت های درج شده برای آگهی');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['average_score', 'comment_count']);
        });
    }
}
