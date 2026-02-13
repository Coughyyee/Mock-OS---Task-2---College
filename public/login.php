<?php
session_start();

// Only ananymous users can acess this endpoint
if (isset($_SESSION['user-logged-in'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Daisyui + Tailwindcss CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- globals css  -->
    <link href="globals.css" rel="stylesheet" />
</head>

<body class="primary-bg min-h-screen flex flex-col ">

    <?php require_once 'components/navbar.php'; ?>

        <!-- Toasts -->
    <?php require_once 'components/toast.php'; ?>

    <main class="flex justify-center items-center flex-grow">

        <div class="card w-96 bg-base-100 shadow-sm">
            <div class="card-body">
                <h1 class="text-3xl font-bold text-center">Login</h1>

                <?php if (isset($_SESSION['error'])) {
                    echo "<p class='text-error text-sm text-center'>{$_SESSION['error']}</p>";
                }
                ?>

                <form method="post" action="../app/service/user-login.php" class="space-y-4">
                    <div class="">
                        <label for="email">Email:</label>
                        <input type="email" name="email" placeholder="account@example.com" class="input w-full" />
                    </div>
                    <div class="">
                        <label for="password">Password:</label>
                        <input type="password" name="password" placeholder="********" minlength="8"
                            class="input w-full" />
                    </div>

                    <button type="submit" class="btn btn-neutral w-full">Login!</button>

                    <p class="text-sm text-center">Don't have an account? 
                        <a href="sign-up.php" class="link link-primary">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </main>


        <!-- <?php require_once 'components/footer.php'; ?> -->
</body>