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
        Schema::table('document_data', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('englishText')->nullable()->change();
            $table->text('hindiText')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_data', function (Blueprint $table) {
            $table->dropColumn('name')->nullable();
            $table->string('englishText')->nullable(false);
            $table->string('hindiText')->nullable(false);
        });
    }
};
