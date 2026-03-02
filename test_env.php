<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "CACHE_DRIVER via env(): " . env('CACHE_DRIVER') . PHP_EOL;
echo "CACHE_DRIVER via getenv(): " . getenv('CACHE_DRIVER') . PHP_EOL;
echo "CACHE_DRIVER via \$_ENV: " . ($_ENV['CACHE_DRIVER'] ?? 'não definido') . PHP_EOL;
