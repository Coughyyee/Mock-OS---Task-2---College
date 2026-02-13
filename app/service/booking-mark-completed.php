<?php

use Database\RiskAssessmentBookingsDatabase;

session_start();

// If not admin -> redirect to home page
if (empty($_SESSION['user-admin']) || $_SESSION['user-admin'] === false) {
    header('Location: ../../public/index.php');
    exit();
}

// ensure there is an id passed through
if (empty($_GET['id'])) {
    header('Location: ../../public/admin.php');
    exit();
}

$id = (int) $_GET['id'];

require_once '../db/riskAssessmentBooking.php';
$db = new RiskAssessmentBookingsDatabase();
$result = $db->getRiskAssessmentsById($id);

if ($result === null) {
    // id is invalid
    $_SESSION['error'] = 'Invalid booking id';
    header('Location: ../../public/admin.php');
    exit();
}

$result = $db->markBookingAsCompletedById($id);
if ($result === false) {
    $_SESSION['error'] = 'Error occured. Please try again.';
    header('Location: ../../public/admin.php');
    exit();
}

$_SESSION['success'] = "Successfully marked booking as complete";
header('Location: ../../public/admin.php');
