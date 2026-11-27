<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('parking_slots')) {
            Schema::create('parking_slots', function (Blueprint $table) {
                $table->id();
                $table->string('slot_number', 20)->unique();
                $table->enum('slot_type', ['Motorcycle', 'Car', 'Van'])
                    ->default('Car');
                $table->enum('status', ['Available', 'Occupied'])
                    ->default('Available');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Preserve existing parking slots and their data.
    }
};
