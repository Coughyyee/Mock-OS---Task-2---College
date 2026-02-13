<?php
session_start();

require_once __DIR__ . '/../app/db/riskAssessmentBooking.php';

use Database\RiskAssessmentBookingsDatabase;

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

$bookingId = (int) $_GET['id']; // TODO: ensure doesnt crash (int).

$db = new RiskAssessmentBookingsDatabase();
$result = $db->getRiskAssessmentsById($bookingId);
if ($result === null) {
    $_SESSION['error'] = "No booking with specified id";
    header('Location: profile.php');
    exit();
}

// ensure the booking belongs to the user
if ($result->userId != $_SESSION['user-id']) {
    header('Location: profile.php');
    exit();
}

// result returns a datetime not a seperate date & time. 
// Below i seperate these 2 values and store them in variables to be then used.
// Splits into an array where the first element is the date and second is the time.
$splitStringArray = explode(' ', $result->datetime);
$date = $splitStringArray[0];
$time = $splitStringArray[1];
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

<body class="primary-bg min-h-screen">

    <!-- Navigation Bar Component -->
    <?php require_once 'components/navbar.php'; ?>

        <!-- Toasts -->
    <?php require_once 'components/toast.php'; ?>


    <main class="px-4 flex justify-center items-center mt-16">
        <div class="card w-128 bg-base-100 shadow-sm" id="booking-form">
            <div class="card-body">
                <h1 class="text-3xl font-bold text-center">Update Risk Assessment Booking</h1>

                <?php if (isset($_SESSION['error'])) {
                    echo "<p class='text-error text-sm text-center'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>

                <form method="post" action="../app/service/booking-update.php?id=<?= $result->bookingId ?>" class="space-y-4 mt-4">
                    <div class="">
                        <label for="fullname">Fullname:</label>
                        <input type="text" name="fullname" placeholder="John Doe" value="<?= htmlspecialchars($result->fullname) ?>"
                            class="input w-full" />
                    </div>
                    <div class="">
                        <label for="phone">Phone:</label>
                        <input type="tel" name="phone" placeholder="07777777777" value="<?= htmlspecialchars($result->phone) ?>"
                            class="input w-full" />
                    </div>
                    <div class="">
                        <label for="address">Address:</label>
                        <input type="text" name="address" placeholder="1 example road" value="<?= htmlspecialchars($result->address) ?>"
                            class="input w-full" />
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="w-48">
                            <label for="date">Date:</label>
                            <input type="date" name="date" class="input w-full" value="<?= htmlspecialchars($date) ?>" />
                        </div>
                        <span class="mt-4">-</span>
                        <div class="w-48">
                            <label for="time">Time:</label>
                            <input type="time" name="time" class="input w-full" value="<?= htmlspecialchars($time) ?>" />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-neutral w-full">Update Booking</button>
                </form>
            </div>
        </div>
    </main>


        <!-- <?php require_once 'components/footer.php'; ?> -->
</body>