<?php

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