<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToGarage;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToGarage;

    protected $fillable = [
        'garage_id',
        'name',
        'phone',
        'email',
        'address',
        'notes',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    public function getVehicleCountAttribute()
    {
        return $this->vehicles()->count();
    }
}