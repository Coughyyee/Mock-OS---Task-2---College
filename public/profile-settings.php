<?php

session_start();

if (empty($_SESSION['user-logged-in']) || empty($_SESSION['user-id'])) {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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


    <main class="mt-32 flex justify-center items-center flex-grow">
        <div class="card w-128 bg-base-100 shadow-sm" id="booking-form">
            <div class="card-body">
                <h1 class="text-3xl font-bold text-center">Settings</h1>

                <ul class="flex justify-around mt-4">
                    <li class="btn"><a href="#">High Contrast</a></li>
                    <li class="btn"><a href="#">Larger Font</a></li>
                </ul>

            </div>
        </div>

    </main>


        <?php require_once 'components/footer.php'; ?>
</body>

</html>