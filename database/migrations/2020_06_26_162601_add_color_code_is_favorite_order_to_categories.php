<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorCodeIsFavoriteOrderToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('color_code')->nullable()->after('en_description')->comment('کد رنگ دسته بندی');
            $table->boolean('is_favorite')->nullable()->after('color_code')->comment('کد رنگ دسته بندی');
            $table->integer('order')->nullable()->after('is_favorite')->comment('تعیین ترتیب دسته بندی');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'color_code', 'is_favorite', 'order'
            ]);
        });
    }
}
