<?php declare(strin_type=1);

namespace App\Enums;

enum Status: string
{
    case AGENDADO = 'AGENDADO';
    case CONCLUIDO = 'CONCLUIDO';
    case CANCELADO = 'CANCELADO';
}