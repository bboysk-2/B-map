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
        Schema::create('spots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            // 緯度、桁数11小数点以下8桁までの精度
            $table->decimal('latitude', 11, 8);
            // 経度、桁数11小数点以下8桁までの精度
            $table->decimal('longitude', 11, 8);
            $table->string('category')->nullable();
            $table->string('floor_material')->nullable();
            $table->string('slipping')->nullable();
            $table->string('space')->nullable();
            $table->string('usage_fee')->nullable();
            $table->string('available_times')->nullable();
            $table->string('volume')->nullable();
            $table->string('reservation')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('spots');
    }
};
