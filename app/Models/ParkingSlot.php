<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSlot extends Model
{
    protected $fillable = [
        'slot_number',
        'slot_type',
        'status',
    ];

    public function parkingRecords(): HasMany
    {
        return $this->hasMany(ParkingRecord::class);
    }
}