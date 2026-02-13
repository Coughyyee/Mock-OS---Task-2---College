<?php

namespace Service;

require_once __DIR__ . '/../db/riskAssessmentBooking.php';

use Database\RiskAssessmentBookingsDatabase;

session_start();

if (empty($_SESSION['user-logged-in'])) {
    header('Location: ../../public/index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id']; 

    // validate id
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        $_SESSION['error'] = 'Invalid booking id';
        header('Location: ../../public/profile.php');
        exit();
    }

    $db = new RiskAssessmentBookingsDatabase();
    $result = $db->deleteBookingById($id);
    if ($result === false) {
        $_SESSION['error'] = "Failed to delete booking.";
        header('Location: ../../public/profile.php');
        exit();
    }

    $_SESSION['success'] = "Successfully deleted booking.";
}

header('Location: ../../public/profile.php');