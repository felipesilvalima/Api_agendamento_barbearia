<?php declare(strict_types=1); 

namespace App\DTOS;

class LoginDTO
{
     public function __construct(
        public string $email,
        public string $password
    ){}
}