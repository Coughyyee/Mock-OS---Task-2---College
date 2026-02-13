<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
                <div class="bg-white rounded-xl px-8 py-8 max-w-[80%] m-4">
                    <h1 class="text-5xl font-bold">Health Advice Group</h1>
                    <h3 class="mt-4 text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem,
                        omnis repudiandae rem assumenda ipsum error ratione rerum blanditiis praesentium natus veritatis
                        nulla eligendi maiores commodi officia, atque animi delectus? Vero.</h3>
                </div>
            </div>

            <div class="">
                <!-- Add asset -->
            </div>
        </div>
    </main>

    <main class="bg-white min-h-[50vh] flex flex-col justify-around mt-8">
        <h1 class="text-5xl font-bold text-center mb-4">About Us</h1>
        <div class="flex lg:grid lg:grid-cols-2 lg:gap-24 mb-4">
            <div class="hidden lg:block">
                <!-- add asset -->
            </div>

            <div class="flex h-full justify-center items-center">
                <div class="border border-black rounded-xl px-8 py-8 max-w-[80%] w-full text-end">
                    <h1 class="text-3xl font-bold">Who Are We?</h1>
                    <h3 class="mt-4 text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem,
                        omnis repudiandae rem assumenda ipsum error ratione rerum blanditiis praesentium natus veritatis
                        nulla eligendi maiores commodi officia, atque animi delectus? Vero.</h3>
                </div>
            </div>
        </div>

        <div class="flex lg:grid lg:grid-cols-2 lg:gap-24 mb-4">
            <div class="flex h-full justify-center items-center">
                <div class="border border-black rounded-xl px-8 py-8 max-w-[80%] w-full">
                    <h1 class="text-3xl font-bold">What We Do.</h1>
                    <h3 class="mt-4 text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem,
                        omnis repudiandae rem assumenda ipsum error ratione rerum blanditiis praesentium natus veritatis
                        nulla eligendi maiores commodi officia, atque animi delectus? Vero.</h3>
                </div>
            </div>

            <div class="hidden lg:block">
                <!-- add asset -->
            </div>
        </div>
    </main>

    <main class="bg-white min-h-[50vh] flex flex-col justify-around mt-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-5xl font-bold text-center mb-8">Services</h1>

        <!-- First service -->
        <div class="flex flex-col lg:grid lg:grid-cols-2 lg:gap-12 xl:gap-24 mb-10 lg:mb-16">
            <div class="hidden lg:flex lg:items-center lg:justify-center">
                <!-- add asset / image here on large screens -->
            </div>

            <div class="flex justify-center items-center">
                <div class="border border-black rounded-xl px-6 py-8 w-full max-w-lg lg:max-w-none">
                    <h1 class="text-3xl font-bold text-right lg:text-end">Personal Health Tracking Tool</h1>
                    <h3 class="mt-4 text-lg text-right lg:text-end">Your very own way to track your health!</h3>
                    <?php if (isset($_SESSION['user-logged-in'])): ?>
                        <a href="profile.php" class="btn btn-neutral w-full mt-10">Check it out!</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-neutral w-full mt-10">Login to get started!</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Second service -->
        <div class="flex flex-col lg:grid lg:grid-cols-2 lg:gap-12 xl:gap-24">
            <div class="flex justify-center items-center order-2 lg:order-1">
                <div class="border border-black rounded-xl px-6 py-8 w-full max-w-lg lg:max-w-none">
                    <h1 class="text-3xl font-bold">Risk Assessment Service</h1>
                    <h3 class="mt-4 text-lg">Book your very own risk assessment!</h3>
                    <a href="risk-assessment.php" class="btn btn-neutral w-full mt-10">Book Now!</a>
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:justify-center order-1 lg:order-2">
                <!-- add asset / image here on large screens -->
            </div>
        </div>
    </main>

    <?php require_once 'components/footer.php'; ?>
</body>

</html>