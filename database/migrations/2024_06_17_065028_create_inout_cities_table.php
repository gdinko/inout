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
        Schema::create('inout_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('county_id')->nullable();
            $table->string('postal_code', 100);
            $table->string('name_local', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('county_name', 255)->nullable();
            $table->string('county_name_en', 255)->nullable();
            $table->string('municipality', 255)->nullable();
            $table->string('state_name', 255)->nullable();
            $table->string('state_name_en', 255)->nullable();
            $table->tinyInteger('manual_edit')->nullable();
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
        Schema::dropIfExists('inout_cities');
    }
};
