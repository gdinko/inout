<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inout_courier_offices', function (Blueprint $table): void {
            $table->unsignedBigInteger('courier_office_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('inout_courier_offices', function (Blueprint $table): void {
            $table->unsignedBigInteger('courier_office_id')->nullable(false)->change();
        });
    }
};
