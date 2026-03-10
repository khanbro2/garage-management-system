
# Create Eloquent Models - Part 1: Trait and Core Models

# First, let's create the BelongsToGarage trait
belongs_to_garage = r'''<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToGarage
{
    protected static function bootBelongsToGarage()
    {
        static::creating(function ($model) {
            if (auth()->check() && !auth()->user()->isSuperAdmin()) {
                $model->garage_id = auth()->user()->garage_id;
            }
        });

        static::addGlobalScope('garage', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin()) {
                $builder->where($builder->getModel()->getTable() . '.garage_id', auth()->user()->garage_id);
            }
        });
    }

    public function garage()
    {
        return $this->belongsTo(\App\Models\Garage::class);
    }
}
'''

# Garage Model
garage_model = r'''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Garage extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'owner_name',
        'email',
        'phone',
        'address',
        'password',
        'status',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'garage_owner');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(GarageSubscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(GarageSubscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getCurrentPlanAttribute()
    {
        return $this->currentSubscription?->plan;
    }

    public function canAddVehicle()
    {
        $plan = $this->current_plan;
        if (!$plan) return false;
        if (is_null($plan->max_vehicles)) return true;
        return $this->vehicles()->count() < $plan->max_vehicles;
    }

    public function canAddStaff()
    {
        $plan = $this->current_plan;
        if (!$plan) return false;
        if (is_null($plan->max_staff)) return true;
        return $this->users()->where('role', 'garage_staff')->count() < $plan->max_staff;
    }

    public function hasSmsReminders()
    {
        return $this->current_plan?->sms_reminders ?? false;
    }

    public function hasApiAccess()
    {
        return $this->current_plan?->api_access ?? false;
    }
}
'''

# User Model
user_model = r'''<?php

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
'''

# Customer Model
customer_model = r'''<?php

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
'''

# Vehicle Model
vehicle_model = r'''<?php

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
'''

# Save the files
import os
os.makedirs('/mnt/kimi/output/app/Traits', exist_ok=True)
os.makedirs('/mnt/kimi/output/app/Models', exist_ok=True)

with open('/mnt/kimi/output/app/Traits/BelongsToGarage.php', 'w') as f:
    f.write(belongs_to_garage)

with open('/mnt/kimi/output/app/Models/Garage.php', 'w') as f:
    f.write(garage_model)

with open('/mnt/kimi/output/app/Models/User.php', 'w') as f:
    f.write(user_model)

with open('/mnt/kimi/output/app/Models/Customer.php', 'w') as f:
    f.write(customer_model)

with open('/mnt/kimi/output/app/Models/Vehicle.php', 'w') as f:
    f.write(vehicle_model)

print("✅ Core Models (Part 1) created successfully")
