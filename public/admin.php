<?php
session_start();

require_once __DIR__ . '/../app/db/riskAssessmentBooking.php';

use Database\RiskAssessmentBookingsDatabase;

// only admin allowed to access this page.
if (
    empty($_SESSION['user-logged-in']) ||
    empty($_SESSION['user-admin']) ||
    $_SESSION['user-admin'] === false
) {
    header('Location: index.php');
    exit();
}

// Get all risk assessments
$db = new RiskAssessmentBookingsDatabase();
$result = $db->getAllRiskAssessments();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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

    <main class="flex flex-col items-center flex-grow">

        <h1 class="text-5xl font-bold mt-8 mb-8">Welcome Admin!</h1>

        <div class="card w-[90vw] bg-base-100 shadow-sm">
            <div class="card-body">
                <h1 class="text-2xl font-bold text-center mb-4">All Risk Assessments</h1>

                <?php if ($result === []): ?>
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
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($result as $row): ?>
                                    <tr>
                                        <th><?= htmlspecialchars($row->fullname); ?></th>
                                        <th><?= htmlspecialchars($row->phone); ?></th>
                                        <th><?= htmlspecialchars($row->address); ?></th>
                                        <th><?= htmlspecialchars($row->datetime); ?></th>
                                        <?php if ($row->completed === true): ?>
                                            <th>
                                                <span class="text-sm text-neutral-400">Completed</span>
                                            </th>
                                        <?php else: ?>
                                            <th><a href="../app/service/booking-mark-completed.php?id=<?= $row->bookingId ?>"
                                                    class="btn btn-success"
                                                    onclick="confirm('Are you sure you want to complete this booking?')">
                                                    Mark As Completed
                                                </a>
                                            </th>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php require_once 'components/footer.php'; ?>
</body>