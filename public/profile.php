<?php

session_start();
require_once __DIR__ . '/../app/db/riskAssessmentBooking.php';
require_once __DIR__ . '/../app/db/healthTracking.php';

use Database\HealthTrackingDatabase;
use Database\RiskAssessmentBookingsDatabase;

if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    header('Location: index.php');
    exit();
}

// store user id from session
$userId = $_SESSION['user-id'];

$riskAssessmentDb = new RiskAssessmentBookingsDatabase();
$riskAssessmentsResult = $riskAssessmentDb->getRiskAssessmentsByUserId($userId);

$healthTrackingDb = new HealthTrackingDatabase();
$healthTrackingPointsResult = $healthTrackingDb->getAllTrackingPointsByUserId($userId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Daisyui + Tailwindcss CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- globals css  -->
    <link href="globals.css" rel="stylesheet" />
</head>

<body class="primary-bg min-h-screen flex flex-col">

    <!-- Navigation Bar Component -->
    <?php require_once 'components/navbar.php'; ?>

    <!-- Toasts -->
    <?php require_once 'components/toast.php'; ?>


    <main class="flex-grow">
        <div class="flex flex-col justify-center">

            <div class="grid grid-cols-6 px-12">
                <div class="col-span-1"></div>
                <h1 class="col-span-4 text-5xl font-bold mt-8 mb-8 text-center">Welcome Back!</h1>
                <div class="col-span-1 flex justify-end items-center">
                    <!-- Accessibility settings page -->
                    <a href="profile-settings.php" class="btn">Settings</a>
                </div>
            </div>

            <section class="grid grid-cols-1 xl:grid-cols-2 px-4 gap-12">
                <div class="card w-full bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h1 class="text-2xl font-bold text-center mb-4">Personal Health Tool</h1>

                        <!-- Previous data -->
                        <div class="bg-base-300 h-64 rounded-xl my-2 mx-2">
                            <!-- Check if data, if not display message -->
                            <?php if ($healthTrackingPointsResult === null): ?>
                                <div class="flex h-full items-center justify-center">
                                    <span class="text-sm text-center text-neutral-500">Start Tracking Today and See Your
                                        Data!</span>
                                </div>
                            <?php else: ?>
                                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Steps</th>
                                                <th>Calorie Intake</th>
                                                <th>Sleep <span class="text-xs text-neutral-500 font-light">(minutes)</span>
                                                </th>
                                                <th>Exercise <span
                                                        class="text-xs text-neutral-500 font-light">(minutes)</span> </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php foreach ($healthTrackingPointsResult as $row): ?>
                                                <tr>
                                                    <th><?= htmlspecialchars($row->entryDate); ?></th>
                                                    <th><?= htmlspecialchars($row->steps); ?></th>
                                                    <th><?= htmlspecialchars($row->calorieIntake); ?></th>
                                                    <th><?= htmlspecialchars($row->sleepMinutes); ?></th>
                                                    <th><?= htmlspecialchars($row->exerciseMinutes); ?></th>
                                                    <th><a href="edit-tracking-point.php?id=<?= $row->id ?>"
                                                            class="btn btn-warning">Edit</a>
                                                    </th>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h2 class="text-xl font-bold text-center">Add New Log</h2>

                        <!-- Add new entry form -->
                        <form method='post' action="../app/service/health-tracking-insert.php"
                            class="grid grid-cols-3 gap-8 mt-4">
                            <div class="col-span-2">
                                <div class="">
                                    <label for="steps">Steps:</label>
                                    <input type="number" name="steps" placeholder="10,000" class="input w-full"
                                        required />
                                </div>
                                <div class="">
                                    <label for="calorie-intake">Calorie Intake:</label>
                                    <input type="number" name="calorie-intake" placeholder="2,000" class="input w-full"
                                        required />
                                </div>
                                <div class="">
                                    <label for="sleep-minutes">Sleep (minutes):</label>
                                    <input type="number" name="sleep-minutes" placeholder="480" class="input w-full"
                                        required />
                                </div>
                                <div class="">
                                    <label for="exercie-minutes">Exercise (minutes):</label>
                                    <input type="number" name="exercise-minutes" placeholder="60" class="input w-full"
                                        required />
                                </div>
                            </div>

                            <div class="flex justify-center items-center">
                                <button type="submit" class="btn btn-neutral w-full">Track Todays Data!</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card w-full bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h1 class="text-2xl font-bold text-center mb-4">Your Risk Assessments</h1>

                        <?php if ($riskAssessmentsResult === null): ?>
                            <!-- If no data returned, the following will display instead of a table -->
                            <span class="text-center text-sm text-gray-300">No Bookings Yet</span>
                        <?php else: ?>
                            <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Date & Time</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($riskAssessmentsResult as $row): ?>
                                            <tr>
                                                <th><?= htmlspecialchars($row->fullname); ?></th>
                                                <th><?= htmlspecialchars($row->phone); ?></th>
                                                <th><?= htmlspecialchars($row->address); ?></th>
                                                <th><?= htmlspecialchars($row->datetime); ?></th>
                                                <th><a href="edit-booking.php?id=<?= $row->bookingId ?>"
                                                        class="btn btn-warning">Edit</a>
                                                </th>
                                                <th><a href="../app/service/booking-cancel.php?id=<?= $row->bookingId ?>"
                                                        class="btn btn-error"
                                                        onclick="confirm('Are you sure you want to delete this booking?')">
                                                        Cancel
                                                    </a>
                                                </th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>


        </div>
    </main>


    <?php require_once 'components/footer.php'; ?>
</body>