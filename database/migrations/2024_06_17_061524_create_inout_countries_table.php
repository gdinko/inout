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
        Schema::create('inout_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name', 255);
            $table->string('cyrillic_name', 255);
            $table->string('iso_code', 20);
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
        Schema::dropIfExists('inout_countries');
    }
};
