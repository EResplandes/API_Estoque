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
        Schema::create('material_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_material');
            $table->unsignedBigInteger('fk_request');
            $table->integer('quantity_request');
            $table->string('observation');
            $table->dateTime('requested_date');
            $table->dateTime('response_date')->nullable();
            $table->unsignedBigInteger('fk_status');
            $table->foreign('fk_material')->references('id')->on('stock');
            $table->foreign('fk_request')->references('id')->on('requests');
            $table->foreign('fk_status')->references('id')->on('transfer_status');
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
        Schema::dropIfExists('material_transfer');
    }
};
