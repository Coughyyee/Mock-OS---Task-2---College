<?php

namespace Service;

require_once __DIR__ . '/../db/riskAssessmentBooking.php';
require_once __DIR__ . '/../models/updateRiskAssessmentDto.php';

use Database\RiskAssessmentBookingsDatabase;
use Models\UpdateRiskAssessmentDto;

use DateTime;

session_start();

// Users without login cannot access this endpoint
if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    // $_SESSION['error'] = 'MUST BE LOGGED IN';
    header('Location: ../../public/index.php');
    exit();
}

// no booking id specfied.
if (empty($_GET['id'])) {
    $_SESSION['error'] = 'Invalid booking id';
    header('Location: ../../public/profile.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fullname = $_POST['fullname'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $userid = $_SESSION['user-id'] ?? null; 
    $bookingid = $_GET['id'] ?? null; // from query param 

    // Validate inputs are there.
    if (
        empty($fullname) ||
        empty($phone) ||
        empty($address) ||
        empty($date) ||
        empty($time) ||
        empty($userid) ||
        empty($bookingid) 
    ) {
        $_SESSION['error'] = 'Empty Input(s)';
        header("Location: ../../public/edit-booking.php?id=$bookingid.php");
        exit();
    }

    // Validate that ids are numbers
    if (
        !filter_var($userid, FILTER_VALIDATE_INT) ||
        !filter_var($bookingid, FILTER_VALIDATE_INT)
    ) {
        $_SESSION['error'] = 'Invalid id(s) provided';
        header("Location: ../../public/edit-booking.php?id=$bookingid.php");
        exit();
    }

    // Validate that the booking cannot be in the past
    $dateTimeObj = new DateTime($date . ' ' . $time);
    $now = new DateTime();

    if ($dateTimeObj <= $now) {
        $_SESSION['error'] = 'Cannot book in the past!';
        header("Location: ../../public/edit-booking.php?id=$bookingid.php");
        exit();
    }

    $datetime = $dateTimeObj->format("Y-m-d H:m:i");

    // Ensure that the booking isnt already taken
    $db = new RiskAssessmentBookingsDatabase();
    $result = $db->getRiskAssessmentsByDatetime($datetime);
    if ($result) {
        $_SESSION['error'] = 'Booking taken';
        header("Location: ../../public/edit-booking.php?id=$bookingid.php");
        exit();
    }

    // create the DTO
    $riskAssessment = new UpdateRiskAssessmentDto(
        $bookingid,
        $userid,
        $fullname,
        $phone,
        $address,
        $datetime
    );

    // Create the booking
    $result = $db->updateBookingById($riskAssessment);
    if ($result === false) {
        $_SESSION['error'] = 'Error occured. Please try again.';
        header("Location: ../../public/edit-booking.php?id=$bookingid.php");
        exit();
    }

    // store session tokens for the user account.
    $_SESSION['success'] = 'Booking updated successfully';
    header('Location: ../../public/profile.php');
    exit();
}

header('Location: ../../public/profile.php');