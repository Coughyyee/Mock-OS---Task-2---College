<?php
session_start();

$co = null;
$no2 = null;
$o3 = null;
$so2 = null;
$usEpaIndex = null;
$gbDefraIndex = null;
$location = null;
$locationInput = '';

if (isset($_GET['weather-location'])) {
    $locationInput = trim($_GET['weather-location']);
    // https://www.weatherapi.com/docs/
    $fullApiStr = 'http://api.weatherapi.com/v1/current.json?key=!&aqi=yes&q=' . urlencode($locationInput);

    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true // allows reading body even on 4xx/5xx
        ]
    ]);

    $request = file_get_contents($fullApiStr, false, $context);

    // Check HTTP status code
    if ($request === false || !isset($http_response_header[0]) || strpos($http_response_header[0], '200') === false) {
        $_SESSION['error'] = 'Invalid location or weather service unavailable';
    } else {
        $result = json_decode($request, true);

        if ($result === null || isset($result['error'])) {
            $_SESSION['error'] = 'Invalid location';
        } else {
            $co = (float) $result['current']['air_quality']['co'];
            $no2 = (float) $result['current']['air_quality']['no2'];
            $o3 = (float) $result['current']['air_quality']['o3'];
            $so2 = (float) $result['current']['air_quality']['so2'];
            $usEpaIndex = (int) $result['current']['air_quality']['us-epa-index'];
            $gbDefraIndex = (int) $result['current']['air_quality']['gb-defra-index'];

            $location = $result['location']['name'];
        }
    }
}

// Message that displays to the user based on the conditions outside.
$jsonMessagesPath = __DIR__ . '/../assets/airQualityMessages.json';
$messagesContents = file_get_contents($jsonMessagesPath);
if ($messagesContents === false) {
    // File doesnt exist
}

$messagesJsonDecoded = json_decode($messagesContents, true);

$message = match ($usEpaIndex) {
    1 => $messagesJsonDecoded['usEpaIndex']['1'],
    2 => $messagesJsonDecoded['usEpaIndex']['2'],
    3 => $messagesJsonDecoded['usEpaIndex']['3'],
    4 => $messagesJsonDecoded['usEpaIndex']['4'],
    5 => $messagesJsonDecoded['usEpaIndex']['5'],
    6 => $messagesJsonDecoded['usEpaIndex']['6'],
    default => ''
};

// echo '<pre>';
// print_r($result);
// echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Quality</title>
    <!-- Daisyui + Tailwindcss CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- globals css  -->
    <link href="globals.css" rel="stylesheet" />
</head>

<body class="primary-bg min-h-screen flex flex-col">

    <!-- Navigation Bar Component -->
    <?php require_once 'components/navbar.php'; ?>

    <main class="px-4 mt-4 flex-grow">
        <div class="w-full ">
            <div class="card bg-white">
                <div class="card-body">
                    <h1 class="text-3xl font-bold text-center">Air Quality</h1>

                    <!-- Input to enter location -->
                    <form method="get" action="" class="flex w-full items-center gap-4 mb-4">
                        <div class="flex-1">
                            <label for="weather-location">Location:</label>
                            <input type="text" name="weather-location" placeholder="Bristol, BS23, London, etc."
                                class="input w-full" value="<?= htmlspecialchars($locationInput) ?? '' ?>" required />
                        </div>

                        <button type="submit" class="btn btn-neutral mt-5 w-32">Get Data!</button>
                    </form>


                    <!-- Weather Api data returned -->
                    <!-- Only works if there is a query param and that there was no error from it. -->
                    <?php if (isset($_GET['weather-location']) && empty($_SESSION['error'])): ?>
                        <!-- Alert changes based on air conditions -->
                        <?php if ($usEpaIndex == 1 || $usEpaIndex == 2): ?>
                            <div class="alert alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <p class="text-md"><?= htmlspecialchars($message) ?></p>
                            </div>
                        <?php elseif ($usEpaIndex == 3 || $usEpaIndex == 4): ?>
                            <div class="alert alert-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>

                                <p class="text-md"><?= htmlspecialchars($message) ?></p>
                            </div>

                        <?php elseif ($usEpaIndex == 5 || $usEpaIndex == 6): ?>
                            <div class="alert alert-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>

                                <p class="text-md"><?= htmlspecialchars($message) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Info -->
                        <div class="">
                            <div class="flex items-center">
                                <h2 class="text-xl font-bold">
                                    Location: <?= htmlspecialchars($location); ?>
                                </h2>

                                <!-- <img src="<?= htmlspecialchars($icon); ?>" alt="Weather Condition" class="w-12" /> -->
                            </div>
                            <ul>
                                <li>Carbon Monoxide (μg/m3): <?= htmlspecialchars($co); ?></li>
                                <li>Ozone (μg/m3): <?= htmlspecialchars($no2); ?></li>
                                <li>Nitrogen dioxide (μg/m3): <?= htmlspecialchars($o3); ?></li>
                                <li>Sulphur dioxide (μg/m3): <?= htmlspecialchars($so2); ?></li>
                                <li>US - EPA standard: <?= htmlspecialchars($usEpaIndex); ?></li>
                                <li>UK Defra Index: <?= htmlspecialchars($gbDefraIndex); ?></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-sm text-neutral-500">No Valid Data, Input something!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>



        <!-- Toasts -->
        <?php require_once 'components/toast.php'; ?>
    </main>


        <?php require_once 'components/footer.php'; ?>
</body>

</html>