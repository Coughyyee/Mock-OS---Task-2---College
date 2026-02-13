<?php

session_start();

require_once __DIR__ . '/../app/db/healthTracking.php';

use Database\HealthTrackingDatabase;

// ensure the user is logged in.
if (empty($_SESSION['user-logged-in'])) {
    header('Location: index.php');
    exit();
}

// Invalid request
if (empty($_GET['id'])) {
    header('Location: profile.php');
    exit();
}

$trackingPointId = (int) $_GET['id']; // TODO: ensure doesnt crash (int).

$db = new HealthTrackingDatabase();
$result = $db->getTrackingPointById($trackingPointId);
if ($result === null) {
    $_SESSION['error'] = "No tracking point with specified id";
    header('Location: profile.php');
    exit();
}

// ensure the booking belongs to the user
if ($result->userId != $_SESSION['user-id']) {
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tracking Point</title>
    <!-- Daisyui + Tailwindcss CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- globals css  -->
    <link href="globals.css" rel="stylesheet" />
</head>

<body class="primary-bg min-h-screen">

    <!-- Navigation Bar Component -->
    <?php require_once 'components/navbar.php'; ?>

        <!-- Toasts -->
    <?php require_once 'components/toast.php'; ?>


    <main class="px-4 flex justify-center items-center mt-16">
        <div class="card w-128 bg-base-100 shadow-sm" id="booking-form">
            <div class="card-body">
                <h1 class="text-3xl font-bold text-center">Update Tracking Point For <?= $result->entryDate ?></h1>

                <?php if (isset($_SESSION['error'])) {
                    echo "<p class='text-error text-sm text-center'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>

                <form method="post" action="../app/service/tracking-point-update.php?id=<?= $_GET['id']; ?>" class="space-y-4 mt-4">
                    <div class="">
                        <label for="steps">Steps:</label>
                        <input type="number" name="steps" placeholder="10,000" value="<?= htmlspecialchars($result->steps) ?>"
                            class="input w-full" />
                    </div>
                    <div class="">
                        <label for="calorie-intake">Calorie Intake:</label>
                        <input type="number" name="calorie-intake" placeholder="2,000" value="<?= htmlspecialchars($result->calorieIntake) ?>"
                            class="input w-full" />
                    </div>
                    <div class="">
                        <label for="sleep-minutes">Sleep (minutes):</label>
                        <input type="number" name="sleep-minutes" placeholder="480" value="<?= htmlspecialchars($result->sleepMinutes) ?>"
                            class="input w-full" />
                    </div>
                    <div class="">
                        <label for="exercise-minutes">Exercise (minutesj):</label>
                        <input type="number" name="exercise-minutes" placeholder="60" value="<?= htmlspecialchars($result->exerciseMinutes) ?>"
                            class="input w-full" />
                    </div>

                    <button type="submit" class="btn btn-neutral w-full">Update Tracking Point</button>
                </form>
            </div>
        </div>
    </main>

    
        <!-- <?php require_once 'components/footer.php'; ?> -->

</body>
</html>