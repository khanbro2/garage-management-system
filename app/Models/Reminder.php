<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToGarage;

class Reminder extends Model
{
    use HasFactory, BelongsToGarage;

    protected $fillable = [
        'garage_id',
        'vehicle_id',
        'customer_id',
        'type',
        'due_date',
        'status',
        'notification_method',
        'sent_at',
        'error_message',
        'days_before',
    ];

    protected $casts = [
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'days_before' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDueForSending($query)
    {
        return $query->where('status', 'pending')
                     ->whereRaw('DATE(due_date) - INTERVAL days_before DAY <= CURDATE()');
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed($error)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error
        ]);
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'mot_expiry' ? 'MOT Expiry' : 'Service Due';
    }
}