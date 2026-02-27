<?php declare(strict_types=1);

namespace App\Cache;
use Illuminate\Support\Facades\Cache;

trait CacheData{

public function verificarCache(string $cacheKey)
{
    if(Cache::has($cacheKey))
    {
        return Cache::get($cacheKey);
    }
}

public function adicionarCache(string $cacheKey, mixed $value, int $duracao)
{
    Cache::put($cacheKey, $value, now()::addMinuto($duracao));
}





}