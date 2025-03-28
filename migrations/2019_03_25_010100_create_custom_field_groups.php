<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 定制字段分组表
        Schema::create('custom_field_groups', function (Blueprint $table) {
            $table->id();
            $table->string('model'); // 模型名
            $table->string('name'); // 分组名
            $table->unsignedInteger('sort'); // 排序
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_groups');
    }
};
