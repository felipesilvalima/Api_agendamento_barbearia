<?php  declare(strict_types=1); 

namespace App\Enums;

enum Status: string
{
    case AGENDADO = 'AGENDADO';
    case CONCLUIDO = 'CONCLUIDO';
    case CANCELADO = 'CANCELADO';
}