<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->comment('نام دسترسی که برنامه با آن کار میکند');
            $table->string('title', 250)->comment('عنوان دسترسی مد نظر');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `roles` comment 'جدول مربوط به دسترسی های کاربران و authorization'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
