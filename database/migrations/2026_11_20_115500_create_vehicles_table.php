<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('plate_number', 20)->unique();
                $table->string('owner_name', 100);
                $table->enum('vehicle_type', ['Motorcycle', 'Car', 'Van']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Preserve existing vehicles and their data.
    }
};
