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
        Schema::table('inclusion_history', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_request')->nullable()->before('data_inclusion');
            $table->foreign('fk_request')->references('id')->on('requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inclusion_history', function (Blueprint $table) {
            //
        });
    }
};
