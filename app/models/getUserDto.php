<?php

namespace Models;

class GetUserDto {
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly bool $isAdmin,
        public readonly string $password,
    ) {}
}