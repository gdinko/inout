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
        Schema::create('inout_courier_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('city_id')->index();
            $table->unsignedBigInteger('courier_id')->index();
            $table->unsignedBigInteger('courier_office_id');
            $table->unsignedBigInteger('group_city_id');
            $table->string('office_name', 255);
            $table->string('courier_office_code', 255)->nullable();
            $table->string('city_name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('latitude', 255)->nullable();
            $table->string('longitude', 255)->nullable();
            $table->string('work_end', 255)->nullable();
            $table->string('work_begin', 255)->nullable();
            $table->string('work_begin_saturday', 255)->nullable();
            $table->string('work_end_saturday', 255)->nullable();
            $table->string('post_code', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->char('city_uuid', 36)->nullable();
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
        Schema::dropIfExists('inout_courier_offices');
    }
};
