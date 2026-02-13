<?php

namespace Database;

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../models/createHealthTrackingPointDto.php';
require_once __DIR__ . '/../models/getHealthTrackingPointDto.php';
require_once __DIR__ . '/../models/updateTrackingPointDto.php';

use Models\CreateHealthTrackingPointDto;
use Models\GetHealthTrackingPointDto;
use Models\UpdateTrackingPointDto;
use PDO;


class HealthTrackingDatabase extends Database
{
    private readonly string $tblName;

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "tblHealthTracking";
    }

    /**
     * Summary of createNewHealthTrackingPoint
     * @param CreateHealthTrackingPointDto $trackingPoint
     * @return bool return true on success and false on failure
     */
    public function createNewHealthTrackingPoint(CreateHealthTrackingPointDto $trackingPoint): bool
    {
        $sql = "INSERT INTO {$this->tblName} (user_id, entry_date, steps, calorie_intake, sleep_minutes, exercise_minutes) VALUES (:id, :ed, :s, :ci, :sm, :em)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $trackingPoint->userId,
            ":ed" => $trackingPoint->entryDate,
            ":s" => $trackingPoint->steps,
            ":ci" => $trackingPoint->calorieIntake,
            ":sm" => $trackingPoint->sleepMinutes,
            ":em" => $trackingPoint->exerciseMinutes
        ]);
    }

    /**
     * Summary of getAllTrackingPointsByUserId
     * @param int $userId
     * @return GetHealthTrackingPointDto[]|null Return the array of Tracking points or null if theres nothing returned.
     */
    public function getAllTrackingPointsByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE user_id = :id ORDER BY entry_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":id" => $userId
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result === [] ? null : array_map(
            fn($row) => new GetHealthTrackingPointDto(
                $row['id'],
                $row['user_id'],
                $row['entry_date'],
                $row['steps'],
                $row['calorie_intake'],
                $row['sleep_minutes'],
                $row['exercise_minutes'],
            ),
            $result
        );
    }

    /**
     * Summary of getTrackingPointByDateAndUserId
     * @param int $userId user id
     * @param string $date date of the tracking point entry
     * @return GetHealthTrackingPointDto|null returns the data object or null
     */
    public function getTrackingPointByDateAndUserId(int $userId, string $date): ?GetHealthTrackingPointDto
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE (user_id, entry_date) = (:uid, :d)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":uid" => $userId,
            ":d" => $date
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? 
            new GetHealthTrackingPointDto(
                $result['id'],
                $result['user_id'],
                $result['entry_date'],
                $result['steps'],
                $result['calorie_intake'],
                $result['sleep_minutes'],
                $result['exercise_minutes'],
            ) : null;
    }

    /**
     * Summary of getTrackingPointById
     * @param int $id tracking point id
     * @return GetHealthTrackingPointDto|null returns the data or null
     */
    public function getTrackingPointById(int $id): ?GetHealthTrackingPointDto {
        $sql = "SELECT * FROM {$this->tblName} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? 
            new GetHealthTrackingPointDto(
                $result['id'],
                $result['user_id'],
                $result['entry_date'],
                $result['steps'],
                $result['calorie_intake'],
                $result['sleep_minutes'],
                $result['exercise_minutes'],
            ) : null;
    }

    /**
     * Summary of updateTrackingPointById
     * @param UpdateTrackingPointDto $trackingPoint
     * @return bool true on success and false on failure
     */
    public function updateTrackingPointById(UpdateTrackingPointDto $trackingPoint): bool
    {
        // update the tracking point only if the id and user_id match, ensuring users can only edit their own bookings not anyone elses.
        $sql = "UPDATE {$this->tblName}
                 SET steps = :s, calorie_intake = :ci, sleep_minutes = :sm, exercise_minutes = :em 
                WHERE id = :id AND user_id = :uid
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $trackingPoint->id,
            ":uid" => $trackingPoint->userId,
            ":s" => $trackingPoint->steps,
            ":ci" => $trackingPoint->calorieIntake,
            ":sm" => $trackingPoint->sleepMinutes,
            ":em" => $trackingPoint->exerciseMinutes
        ]);
    }
}