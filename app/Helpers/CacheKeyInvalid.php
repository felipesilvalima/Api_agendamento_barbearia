<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

trait CacheKeyInvalid{


    protected static function bootCacheKeyInvalid()
    {
       static $user = auth('api')->user()->id;

         static::created(function ($model) {
            Cache::tags([$model->getTable()])->flush();
        });
        
         static::updated(function ($model) {
            Cache::tags([$model->getTable()])->flush();
        });

         static::deleted(function ($model) {
            Cache::tags([$model->getTable()])->flush();
        });

         static::saved(function ($model) {
            Cache::tags([$model->getTable()])->flush();
        });

         static::restored(function ($model) {
            Cache::tags([$model->getTable()])->flush();
        });

        
    }

}