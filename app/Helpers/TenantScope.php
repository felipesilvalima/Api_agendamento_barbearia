<?php declare(strict_types=1); 

namespace app\Helpers;

trait TenantScope
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check()) {
                $query->where('barbearia_id', auth()->user()->barbearia_id);
            }
        });
    }
}