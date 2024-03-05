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
        Schema::table('material_transfer', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_request')->before('destiny');
            $table->integer('quantity_request');
            $table->dateTime('requested_date');
            $table->dateTime('response_date');
            $table->unsignedBigInteger('fk_status');
            $table->foreign('fk_request')->references('id')->on('requests');
            $table->foreign('fk_status')->references('id')->on('transfer_status');
        });
    }

    /**s
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
