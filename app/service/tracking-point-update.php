<?php

namespace Service;

use Database\HealthTrackingDatabase;
use Models\UpdateTrackingPointDto;

session_start();

// Users without login cannot access this endpoint
if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    // $_SESSION['error'] = 'MUST BE LOGGED IN';
    header('Location: ../../public/index.php');
    exit();
}

// no booking id specfied.
if (empty($_GET['id'])) {
    $_SESSION['error'] = 'Invalid tracking point id';
    header('Location: ../../public/profile.php');
    exit();
}

$id = (int) $_GET['id'];

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
        header("Location: ../../public/edit-tracking-point.php?id=$id");
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
        header("Location: ../../public/edit-tracking-point.php?id=$id");
        exit();
    }


    require_once __DIR__ . '/../db/healthTracking.php';
    $db = new HealthTrackingDatabase();

    $trackingPoint = new UpdateTrackingPointDto(
        $id,
        $userId,
        $steps,
        $calorieIntake,
        $sleepMinutes,
        $exerciseMinutes 
    );

    $result = $db->updateTrackingPointById($trackingPoint);
        if ($result === false) {
        $_SESSION['error'] = 'Error occured. Please try again.';
        header("Location: ../../public/edit-tracking-point.php?id=$id");
        exit();
    }

    // store session tokens for the user account.
    $_SESSION['success'] = 'Booking updated successfully';
    header('Location: ../../public/profile.php');
    exit();
}

header('Location: ../../public/profile.php');