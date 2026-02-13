<?php

namespace Service;

use Database\HealthTrackingDatabase;
use DateTime;
use Models\CreateHealthTrackingPointDto;

session_start();

// Users without login cannot access this endpoint
if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    // $_SESSION['error'] = 'MUST BE LOGGED IN';
    header('Location: ../../public/risk-assessment.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $steps = $_POST['steps'] ?? null;
    $calorieIntake = $_POST['calorie-intake'] ?? null;
    $sleepMinutes = $_POST['sleep-minutes'] ?? null;
    $exerciseMinutes = $_POST['exercise-minutes'] ?? null;

    $userId = $_SESSION['user-id'] ?? null;

    // ensure that the inputs exist
    if (
        empty($steps) ||
        empty($calorieIntake) ||
        empty($sleepMinutes) ||
        empty($exerciseMinutes) ||
        empty($userId)
    ) {
        $_SESSION['error'] = 'Empty Input(s)';
        header('Location: ../../public/profile.php');
        exit();
    }

    // ensure all user inputs are integers
    if (
        !filter_var($steps, FILTER_VALIDATE_INT) ||
        !filter_var($calorieIntake, FILTER_VALIDATE_INT) ||
        !filter_var($sleepMinutes, FILTER_VALIDATE_INT) ||
        !filter_var($exerciseMinutes, FILTER_VALIDATE_INT) 
    ) {
        $_SESSION['error'] = 'Invalid Input(s)';
        header('Location: ../../public/profile.php');
        exit();
    }

    require_once __DIR__ . '/../db/healthTracking.php';
    $db = new HealthTrackingDatabase();
    
    // check if there are any tracking points done today
    $nowDate = new DateTime()->format("Y-m-d");
    $result = $db->getTrackingPointByDateAndUserId($userId, $nowDate);
    if ($result !== null) {
        $_SESSION['error'] = 'You have already submitted a tracking point for today.';
        header('Location: ../../public/profile.php');
        exit();
    }

    // create a new tracking point
    $trackingPoint = new CreateHealthTrackingPointDto(
        $userId,
        $nowDate,
        $steps, 
        $calorieIntake,
        $sleepMinutes,
        $exerciseMinutes,
    );
    
    // insert tracking point into database.
    $result = $db->createNewHealthTrackingPoint($trackingPoint);
    if ($result === false) {
        $_SESSION['error'] = 'Error creating tracking point. Please try again.';
        header('Location: ../../public/profile.php');
        exit();
    }

    $_SESSION['success'] = "Successfully created tracking point for: $nowDate";
}

header('Location: ../../public/profile.php');