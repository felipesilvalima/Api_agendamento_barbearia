<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

trait CacheData{

public function verificarCache(string $cacheKey):mixed
{
    if(Cache::has($cacheKey))
    {
        return Cache::get($cacheKey);
    }
}

public function adicionarCache(string $cacheKey, mixed $value, int $duracao):void
{
    $tag = explode('-',$cacheKey);
    Cache::tags([$tag[0]])->put($cacheKey, $value, now()::addMinuto($duracao));
}





}