<?php

namespace Database;

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../models/createRiskAssessmentDto.php';
require_once __DIR__ . '/../models/getRiskAssessmentDto.php';
require_once __DIR__ . '/../models/updateRiskAssessmentDto.php';

use Models\CreateRiskAssessmentDto;
use Models\GetRiskAssessmentDto;
use Models\UpdateRiskAssessmentDto;
use PDO;

class RiskAssessmentBookingsDatabase extends Database
{
    private readonly string $tblName;

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "tblRiskAssessments";
    }

    /** 
     * Summary of createNewRiskAssessmentBooking
     * @param CreateRiskAssessmentDto $riskAssessment
     * @return bool True on success and false on failure.
     */
    public function createNewRiskAssessmentBooking(CreateRiskAssessmentDto $riskAssessment): bool
    {
        $sql = "INSERT INTO {$this->tblName} (user_id, fullname, phone, address, datetime) VALUES (:id, :fn, :p, :a, :dt)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $riskAssessment->userId,
            ":fn" => $riskAssessment->fullname,
            ":p" => $riskAssessment->phone,
            ":a" => $riskAssessment->address,
            ":dt" => $riskAssessment->datetime
        ]);
    }

    /**
     * Summary of getRiskAssessmentsByUserId
     * @param int $id
     * @return ?GetRiskAssessmentDto[] Users risk assessments, if empty then return null
     */
    public function getRiskAssessmentsByUserId(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result === [] ? null : array_map(
            fn($row) => new GetRiskAssessmentDto(
                $row['id'],
                $row['user_id'],
                $row['fullname'],
                $row['phone'],
                $row['address'],
                $row['datetime'],
                $row['completed']
            ),
            $result
        );
    }

    /**
     * Summary of getRiskAssessmentsByDatetime
     * @param string $datetime
     * @return ?GetRiskAssessmentDto return the data, if no data then null returned.
     */
    public function getRiskAssessmentsByDatetime(string $datetime): ?GetRiskAssessmentDto
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE datetime = :dt";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":dt" => $datetime
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new GetRiskAssessmentDto(
            $result['id'],
            $result['user_id'],
            $result['fullname'],
            $result['phone'],
            $result['address'],
            $result['datetime'],
                $result['completed']
        ) : null;
    }

    /**
     * Summary of getAllRiskAssessments
     *  ADMIN ONLY REQUEST
     *  @return ?GetRiskAssessmentDto[] Every risk assessment in database, if empty return null.
     */
    public function getAllRiskAssessments(): ?array
    {
        $sql = "SELECT * FROM {$this->tblName}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result === [] ? null : array_map(
            fn($row) => new GetRiskAssessmentDto(
                $row['id'],
                $row['user_id'],
                $row['fullname'],
                $row['phone'],
                $row['address'],
                $row['datetime'],
                $row['completed'],
            ),
            $result
        );
    }

    /**
     * Summary of deleteBookingById
     * @param int $id the booking id
     * @return bool True on success and false on failure
     */
    public function deleteBookingById(int $id): bool
    {
        $sql = "DELETE FROM {$this->tblName} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id
        ]);
    }

    /**
     * Summary of getRiskAssessmentsById
     * @param int $id booking id
     * @return ?GetRiskAssessmentDto returns the data, if no data returned then null returned
     */
    public function getRiskAssessmentsById(int $id): ?GetRiskAssessmentDto
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new GetRiskAssessmentDto(
            $result['id'],
            $result['user_id'],
            $result['fullname'],
            $result['phone'],
            $result['address'],
            $result['datetime'],
            $result['completed'],
        ) : null;
    }

    /**
     * Summary of updateBookingById
     * @param UpdateRiskAssessmentDto $riskAssessment
     * @return bool returns true on success and false on failure.
     */
    public function updateBookingById(UpdateRiskAssessmentDto $riskAssessment): bool
    {
        // update the booking only if the id and user_id match, ensuring users can only edit their own bookings not anyone elses.
        $sql = "UPDATE {$this->tblName}
                 SET fullname = :fn, phone = :p, address = :a, datetime = :dt 
                WHERE id = :id AND user_id = :uid
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":fn" => $riskAssessment->fullname,
            ":p" => $riskAssessment->phone,
            ":a" => $riskAssessment->address,
            ":dt" => $riskAssessment->datetime,
            ":id" => $riskAssessment->bookingId,
            ":uid" => $riskAssessment->userId,
        ]);
    }

    /**
     * Summary of markBookingAsCompletedById
     * @param int $id booking id
     * @return bool true on success and false on failure
     */
    public function markBookingAsCompletedById(int $id): bool {
        $sql = "UPDATE {$this->tblName}
                 SET completed = 1
                WHERE id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id
        ]);
    }
}