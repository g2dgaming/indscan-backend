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
        Schema::create('aadhar_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('uid');
            $table->unsignedBigInteger('document_data_id');
            $table->foreign('document_data_id')->references('id')->on('document_data');
            $table->string('address')->nullable();
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
        Schema::dropIfExists('aadhar_cards');
    }
};
