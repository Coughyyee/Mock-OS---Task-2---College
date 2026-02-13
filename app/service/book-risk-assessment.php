<?php

namespace Service;

require_once __DIR__ . '/../db/riskAssessmentBooking.php';
require_once __DIR__ . '/../models/createRiskAssessmentDto.php';

use Database\RiskAssessmentBookingsDatabase;
use Models\CreateRiskAssessmentDto;

use DateTime;

session_start();

// Users without login cannot access this endpoint
if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    // $_SESSION['error'] = 'MUST BE LOGGED IN';
    header('Location: ../../public/risk-assessment.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fullname = $_POST['fullname'] ?? null;
    $phone = $_POST['phone'] ?? null;  
    $address = $_POST['address'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $userid = $_SESSION['user-id'] ?? null; 

    // Validate inputs are there.
    if (
        empty($fullname) ||
        empty($phone) ||
        empty($address) ||
        empty($date) ||
        empty($time) ||
        empty($userid)
    ) {
        $_SESSION['error'] = 'Empty Input(s)';
        header('Location: ../../public/risk-assessment.php');
        exit();
    }

    // Validate that the booking cannot be in the past
    $dateTimeObj = new DateTime($date . ' ' . $time);
    $now = new DateTime();

    if ($dateTimeObj <= $now) {
        $_SESSION['error'] = 'Cannot book in the past!';
        header('Location: ../../public/risk-assessment.php');
        exit();
    }

    $datetime = $dateTimeObj->format("Y-m-d H:m:i");

    // Ensure that the booking isnt already taken
    $db = new RiskAssessmentBookingsDatabase();
    $result = $db->getRiskAssessmentsByDatetime($datetime);
    if ($result) {
        $_SESSION['error'] = 'Booking taken';
        header('Location: ../../public/risk-assessment.php');
        exit();
    }

    // Create the DTO
    $riskAssessment = new CreateRiskAssessmentDto(
        $userid, 
        $fullname, 
        $phone, 
        $address, 
        $datetime
    );

    // Create the booking
    $result = $db->createNewRiskAssessmentBooking($riskAssessment);
    if ($result === false) {
        $_SESSION['error'] = 'Error occured. Please try again.';
        header('Location: ../../public/risk-assessment.php');
        exit();
    }

    // store session tokens for the user account.
    $_SESSION['success'] = 'Booking booked successfully';
    header('Location: ../../public/index.php');
    exit();
}

header('Location: ../../public/risk-assessment.php');