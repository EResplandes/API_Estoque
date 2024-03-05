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
        Schema::create('application_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_request');
            $table->unsignedBigInteger('fk_material');
            $table->integer('amount');
            $table->integer('amount_approved')->nullable();
            $table->boolean('status')->nullable();
            $table->foreign('fk_request')->references('id')->on('requests');
            $table->foreign('fk_material')->references('id')->on('stock');
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
        Schema::dropIfExists('application_materials');
    }
};
