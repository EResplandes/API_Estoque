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
            $table->string('destiny');
            $table->string('origin');
            $table->string('status');
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
        Schema::dropIfExists('material_transfer');
    }
};
