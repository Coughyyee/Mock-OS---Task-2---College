<?php

namespace Models;

class CreateHealthTrackingPointDto {
    public function __construct(
        public readonly int $userId,
        public readonly string $entryDate,
        public readonly int $steps,
        public readonly int $calorieIntake,
        public readonly int $sleepMinutes,
        public readonly int $exerciseMinutes,
    ) {}
}