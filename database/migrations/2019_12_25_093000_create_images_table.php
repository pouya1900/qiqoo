<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id')->comment('شناسه تصویر');
            $table->string('title')->comment('نام کامل تصویر (با پسوند)');
            $table->string('model_type')->nullable()->comment('تعیین اینکه تصویر مربوط به کدام بخش می باشد. (تعیین موارد مجاز در کانفیگ امکان پذیر می باشد)');
            $table->string('ext')->comment('پسوند تصویر');
            $table->integer('size')->comment('حجم تصویر');
            $table->integer('width')->comment('تعیین عرض تصویر');
            $table->integer('height')->comment('ارتفاع تصویر');
            $table->string('imagable_type')->nullable()->comment('تعیین model موردنظر');
            $table->integer('imagable_id')->nullable()->comment('تعیین رکورد موردنظر');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `images` comment 'جدول morph نگهداری اطلاعات مربوط به تصاویر استفاده شده برای موجودیت های مختلف نرم افزار'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
