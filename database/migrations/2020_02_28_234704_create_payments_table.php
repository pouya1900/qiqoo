<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('ads_id')->index()->comment('تعیین آگهی مربوط به این پرداخت');
            $table->string('description')->comment('توضیحات پرداخت');
            $table->integer('user_id')->index()->comment('اطلاعات کاربر پرداخت کننده');
            $table->decimal('price', 12, 0)->comment('مبلغ قابل پرداخت');
            $table->string('trans_id')->nullable()->comment('شناسه تراکنش');
            $table->string('card_number')->nullable()->comment('شماره کارت پرداخت کننده');
            $table->boolean('is_success')->default(0)->comment('تعیین اینکه پرداخت موفقیت آمیز بوده است');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `payments` comment 'جدول مربوط به پرداخت های کاربران'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
