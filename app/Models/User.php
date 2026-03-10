<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'garage_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isGarageOwner()
    {
        return $this->role === 'garage_owner';
    }

    public function isGarageStaff()
    {
        return $this->role === 'garage_staff';
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function canManageStaff()
    {
        return $this->isGarageOwner() || $this->isSuperAdmin();
    }

    public function canManageSubscription()
    {
        return $this->isGarageOwner() || $this->isSuperAdmin();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGarage($query, $garageId)
    {
        return $query->where('garage_id', $garageId);
    }
}