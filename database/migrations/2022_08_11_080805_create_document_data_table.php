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
        Schema::create('document_data', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id');
            $table->string('image');            
            $table->boolean('is_aadhar_card')->default(false);
            $table->boolean('is_pan_card')->default(false);
            $table->text('englishText');
            $table->text('hindiText');
            $table->text('entities');            
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
        Schema::dropIfExists('document_data');
    }
};
