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
        Schema::create('inout_company_couriers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_id');
            $table->string('name', 255);
            $table->tinyInteger('to_office')->default(0);
            $table->tinyInteger('to_address')->default(0);
            $table->string('country', 255);
            $table->string('currency', 255);
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
        Schema::dropIfExists('company_couriers');
    }
};
