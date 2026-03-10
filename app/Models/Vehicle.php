<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToGarage;
use Carbon\Carbon;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes, BelongsToGarage;

    protected $fillable = [
        'garage_id',
        'customer_id',
        'registration_number',
        'make',
        'model',
        'year',
        'vin',
        'color',
        'mileage',
        'mot_expiry',
        'service_due',
        'last_mot_check',
    ];

    protected $casts = [
        'mot_expiry' => 'date',
        'service_due' => 'date',
        'last_mot_check' => 'date',
        'year' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function motRecords()
    {
        return $this->hasMany(MotRecord::class)->orderBy('test_date', 'desc');
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class)->orderBy('service_date', 'desc');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('registration_number', 'like', "%{$term}%")
              ->orWhere('make', 'like', "%{$term}%")
              ->orWhere('model', 'like', "%{$term}%")
              ->orWhereHas('customer', function ($cq) use ($term) {
                  $cq->where('name', 'like', "%{$term}%")
                     ->orWhere('phone', 'like', "%{$term}%");
              });
        });
    }

    public function scopeMotExpiringSoon($query, $days = 30)
    {
        $future = Carbon::now()->addDays($days);
        return $query->where('mot_expiry', '<=', $future)
                     ->where('mot_expiry', '>=', Carbon::now());
    }

    public function scopeServiceDueSoon($query, $days = 30)
    {
        $future = Carbon::now()->addDays($days);
        return $query->where('service_due', '<=', $future)
                     ->where('service_due', '>=', Carbon::now());
    }

    public function getMotStatusAttribute()
    {
        if (!$this->mot_expiry) return 'unknown';
        $now = Carbon::now();
        $expiry = Carbon::parse($this->mot_expiry);
        
        if ($expiry->isPast()) return 'expired';
        if ($expiry->diffInDays($now) <= 7) return 'expiring_soon';
        if ($expiry->diffInDays($now) <= 30) return 'expiring';
        return 'valid';
    }

    public function getServiceStatusAttribute()
    {
        if (!$this->service_due) return 'unknown';
        $now = Carbon::now();
        $due = Carbon::parse($this->service_due);
        
        if ($due->isPast()) return 'overdue';
        if ($due->diffInDays($now) <= 7) return 'due_soon';
        if ($due->diffInDays($now) <= 30) return 'due';
        return 'ok';
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->registration_number})";
    }
}