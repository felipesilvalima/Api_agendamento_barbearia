<?php declare(string_type=1); declare(strict_types=1); 

namespace App\Enums;

enum Role: string
{
    CASE CLIENTE = 'cliente';
    CASE BARBEIRO = 'barbeiro';
    CASE ADMIN = 'admin';
}