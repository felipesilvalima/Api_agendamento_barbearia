<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

trait CacheKeyInvalid{

    protected static function bootCacheKeyInvalid()
    {

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

            if (method_exists(Cache::store(), 'tags')) {
                Cache::tags([$model->getTable()])->flush();
            }
            
        });

       

        
    }

}