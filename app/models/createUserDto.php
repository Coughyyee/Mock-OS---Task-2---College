<?php

namespace Models;

class CreateUserDto {
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}