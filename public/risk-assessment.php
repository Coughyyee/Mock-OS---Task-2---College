<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Assessment</title>
    <!-- Daisyui + Tailwindcss CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- globals css  -->
    <link href="globals.css" rel="stylesheet" />
</head>

<body class="bg-white min-h-screen">

    <!-- Navigation Bar Component -->
    <?php require_once 'components/navbar.php'; ?>

        <!-- Toasts -->
    <?php require_once 'components/toast.php'; ?>

    <main class="primary-bg px-4">
        <div class="flex lg:grid lg:grid-cols-2 lg:gap-24 min-h-[50vh]">
            <div class="flex justify-center items-center">
                <div class="bg-white rounded-xl px-8 py-8 max-w-[80%]">
                    <h1 class="text-5xl font-bold">Risk Assessments</h1>
                    <h3 class="mt-4 text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem,
                        omnis repudiandae rem assumenda ipsum error ratione rerum blanditiis praesentium natus veritatis
                        nulla eligendi maiores commodi officia, atque animi delectus? Vero.</h3>
                    <a href="risk-assessment.php#bookings-form" class="btn btn-neutral w-full mt-4">Book Now!</a>
                </div>
            </div>

            <div class="">
                <!-- Add asset -->
            </div>
        </div>
    </main>

    <main class="bg-white px-4 flex justify-center items-center mt-16">
        <div class="card w-128 bg-base-100 shadow-sm" id="booking-form">
            <div class="card-body">
                <h1 class="text-3xl font-bold text-center">Book Risk Assessment</h1>

                <?php if (isset($_SESSION['error'])) {
                    echo "<p class='text-error text-sm text-center'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                } else if (empty($_SESSION['user-logged-in'])) {
                    echo "<p class='text-error text-sm text-center'>Must login to book!</p>";
                }
                ?>

                <form method="post" action="../app/service/book-risk-assessment.php" class="space-y-4 mt-4">
                    <div class="">
                        <label for="fullname">Fullname:</label>
                        <input type="text" name="fullname" placeholder="John Doe" class="input w-full" required />
                    </div>
                    <div class="">
                        <label for="phone">Phone:</label>
                        <input type="tel" name="phone" placeholder="07777777777" class="input w-full" required />
                    </div>
                    <div class="">
                        <label for="address">Address:</label>
                        <input type="text" name="address" placeholder="1 example road" class="input w-full" required />
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="w-48">
                            <label for="date">Date:</label>
                            <input type="date" name="date" class="input w-full" required />
                        </div>
                        <span class="mt-4">-</span>
                        <div class="w-48">
                            <label for="time">Time:</label>
                            <input type="time" name="time" class="input w-full" required />
                        </div>
                    </div>



                    <?php if (isset($_SESSION['user-logged-in'])): ?>
                        <button type="submit" class="btn btn-neutral w-full">Book Now!</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-neutral w-full" disabled>Book Now!</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </main>

    
        <?php require_once 'components/footer.php'; ?>

</body>