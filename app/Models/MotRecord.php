<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'test_date',
        'result',
        'mileage',
        'expiry_date',
        'notes',
        'defects',
        'mot_test_number',
    ];

    protected $casts = [
        'test_date' => 'date',
        'expiry_date' => 'date',
        'defects' => 'array',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getIsPassAttribute()
    {
        return $this->result === 'pass';
    }
}