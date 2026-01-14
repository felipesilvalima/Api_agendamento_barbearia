<?php declare(strict_types=1); 

namespace App\DTOS;

class LoginDtos
{
     public function __construct(
        public string $email,
        public string $password
    ){}
}