<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCategoriablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoriables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->index()->comment('تعیین مشخصات دسته بندی');
            $table->integer('categoriable_id')->index()->comment('تعیین شناسه فیلدی که این دسته بندی را دارد');
            $table->string('categoriable_type', 50)->comment('تعیین جدولی که این دسته بندی را دارد:فیلتر یا ... (در آینده شاید ماژولی به این نیاز داشته باشد)');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `categoriables` comment 'جدول میانی بین دسته بندی و فیلترها از نوع morph_many'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoriables');
    }
}
