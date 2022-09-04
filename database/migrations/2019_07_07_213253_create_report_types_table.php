<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateReportTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 500)->comment('عنوان نوع گزاش');
            $table->string('description', 2000)->comment('توضیح نوع گزارش (اختیاری)');
            $table->integer('importance_level')->comment('تعیین کننده میزان مهم بودن گزارش: از 1 تا 5');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `report_types` comment 'جدول مربوط به نوع گزارش'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_types');
    }
}
