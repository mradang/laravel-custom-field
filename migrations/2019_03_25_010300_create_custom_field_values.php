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
        // 定制字段数据
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->string('valuetable_type'); // 对应所属模型的类名
            $table->unsignedBigInteger('valuetable_id'); // 对应所属模型的 ID
            $table->unsignedBigInteger('no'); // 序号
            $table->longText('data'); // 字段数据
            $table->timestamps();
            $table->index(['valuetable_type', 'valuetable_id']); // 索引
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_values');
    }
};
