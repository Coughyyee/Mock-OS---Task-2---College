<?php

namespace Models;

class CreateRiskAssessmentDto {
    public function __construct(
        public readonly int $userId,
        public readonly string $fullname,
        public readonly int $phone,
        public readonly string $address,
        public readonly string $datetime,
    ) {}
}