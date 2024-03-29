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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->string('name')->uniqid();
            $table->char('description');
            $table->integer('amount');
            $table->dateTime('dt_validity');
            $table->unsignedBigInteger('fk_companie');
            $table->unsignedBigInteger('fk_category');
            $table->string('image_directory');
            $table->foreign('fk_companie')->references('id')->on('companies');
            $table->foreign('fk_category')->references('id')->on('category');
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
        Schema::dropIfExists('stock');
    }
};
