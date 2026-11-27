<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('parking_records')) {
            Schema::create('parking_records', function (Blueprint $table) {
                $table->id();

                $table->foreignId('vehicle_id')
                    ->constrained('vehicles')
                    ->restrictOnDelete();

                $table->foreignId('parking_slot_id')
                    ->constrained('parking_slots')
                    ->restrictOnDelete();

                $table->dateTime('entry_time')
                    ->useCurrent();

                $table->dateTime('exit_time')->nullable();
                $table->decimal('parking_fee', 10, 2)->default(0);

                $table->enum('status', ['Parked', 'Completed'])
                    ->default('Parked');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Preserve existing parking records and their data.
    }
};
