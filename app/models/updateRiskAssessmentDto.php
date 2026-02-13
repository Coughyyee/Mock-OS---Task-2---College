<?php

namespace Models;

class UpdateRiskAssessmentDto {
    public function __construct(
        public readonly int $bookingId,
        public readonly int $userId,
        public readonly string $fullname,
        public readonly int $phone,
        public readonly string $address,
        public readonly string $datetime,
    ) {}
}