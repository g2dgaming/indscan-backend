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
        Schema::create('pan_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('pan_number')->nullable();
            $table->unsignedBigInteger('document_data_id');
            $table->foreign('document_data_id')->references('id')->on('document_data');
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
        Schema::dropIfExists('pan_cards');
    }
};
