<?php
session_start();

// Get health advice information from json file.
$jsonMessagesPath = __DIR__ . '/../assets/informationAdvice.json';
$messagesContents = file_get_contents($jsonMessagesPath);
if ($messagesContents === false) {
    // File doesnt exist
}

$messages = json_decode($messagesContents, true);
// echo '<pre>';
// print_r($messages);
// echo '</pre>';
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

    <main class="flex-grow">
        <div class="flex flex-col justify-center">
            <h1 class="text-5xl font-bold mt-8 mb-8 text-center">Information & Advice</h1>

            <section class="px-12 space-y-4">
                <?php foreach ($messages as $info => $advice): ?>
                    <div class="collapse collapse-arrow bg-base-100 border border-base-300">
                        <input type="radio" name="my-accordion-2" checked="checked" />
                        <div class="collapse-title font-semibold"><?= htmlspecialchars($info) ?></div>
                        <div class="collapse-content text-sm"><?= htmlspecialchars($advice) ?></div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <?php require_once 'components/toast.php'; ?>


    <?php require_once 'components/footer.php'; ?>
</body>