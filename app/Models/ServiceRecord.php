<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'service_date',
        'service_type',
        'description',
        'technician',
        'cost',
        'mileage',
        'notes',
        'parts_used',
        'next_service_due',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_service_due' => 'date',
        'cost' => 'decimal:2',
        'parts_used' => 'array',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const SERVICE_TYPES = [
        'interim' => 'Interim Service',
        'full' => 'Full Service',
        'major' => 'Major Service',
        'repair' => 'Repair',
        'diagnostic' => 'Diagnostic',
    ];

    public function getServiceTypeLabelAttribute()
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }
}