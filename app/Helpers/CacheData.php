<?php declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait CacheData{

public function verificarCache(string $cacheKey)
{
    if(Cache::has($cacheKey))
    {
        return Cache::get($cacheKey);
    }
}

public function adicionarCache(string $cacheKey, mixed $value, int | string $duracao):void
{
    $tag = explode('-',$cacheKey);
    Cache::tags([$tag[0]])->put($cacheKey, $value, Carbon::now()->addMinute((int)$duracao));
}





}