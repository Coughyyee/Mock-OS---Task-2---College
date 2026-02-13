<?php
session_start();

$icon = null;
$location = null;
$tempC = null;
$tempCFeelsLike = null;
$tempF = null;
$tempFFeelsLike = null;
$windMph = null;
$uv = null;
$locationInput = '';

if (isset($_GET['weather-location'])) {
    $locationInput = trim($_GET['weather-location']);
    // https://www.weatherapi.com/docs/
    $fullApiStr = 'http://api.weatherapi.com/v1/current.json?key=!&q=' . urlencode($locationInput);

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
            $icon = $result['current']['condition']['icon'];
            $location = $result['location']['name'];
            $tempC = (float) $result['current']['temp_c'];
            $tempCFeelsLike = (float) $result['current']['feelslike_c'];
            $tempF = (float) $result['current']['temp_f'];
            $tempFFeelsLike = (float) $result['current']['feelslike_f'];
            $windMph = (float) $result['current']['wind_mph'];
            $uv = (float) $result['current']['uv'];
        }
    }
}

// Message that displays to the user based on the conditions outside.
$jsonMessagesPath = __DIR__ . '/../assets/weatherMessages.json';
$messagesContents = file_get_contents($jsonMessagesPath);
if ($messagesContents === false) {
    // File doesnt exist
}

$messagesJsonDecoded = json_decode($messagesContents, true);

$message = "";

// temperature
if ($tempC > 25) {
    $message .= $messagesJsonDecoded['hot'] . ' ';
} else if ($tempC < 8) {
    $message .= $messagesJsonDecoded['cold'] . ' ';
} else if ($tempC < 0) {
    $message .= $messagesJsonDecoded['freezing'] . ' ';
}

// wind speed
if ($windMph > 25) {
    $message .= $messagesJsonDecoded['windy'] . ' ';
}

// uv
if ($uv > 6) {
    $message .= $messagesJsonDecoded['highUv'] . ' ';
}

// if nothing added on set to default messages
if ($message === "") {
    $message .= $messagesJsonDecoded['empty'] . ' ';
}

// echo '<pre>';
// print_r($result);
// echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather</title>
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
                    <h1 class="text-3xl font-bold text-center">Weather Conditions</h1>

                    <!-- Input to enter location -->
                    <form method="get" action="" class="flex w-full items-center gap-4 mb-4">
                        <div class="flex-1">
                            <label for="weather-location">Location:</label>
                            <input type="text" name="weather-location" placeholder="Bristol, BS23, London, etc."
                                class="input w-full" value="<?= htmlspecialchars($locationInput) ?>" required />
                        </div>

                        <button type="submit" class="btn btn-neutral mt-5 w-32">Get Data!</button>
                    </form>


                    <!-- Weather Api data returned -->
                    <!-- Only works if there is a query param and that there was no error from it. -->
                    <?php if (isset($_GET['weather-location']) && empty($_SESSION['error'])): ?>
                        <!-- Message -->
                        <div class="alert alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="h-6 w-6 shrink-0 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>

                            <p class="text-md"><?= htmlspecialchars($message) ?></p>
                        </div>

                        <div class="">
                            <div class="flex items-center">
                                <h2 class="text-xl font-bold">
                                    Location: <?= htmlspecialchars($location); ?>
                                    (<?= htmlspecialchars($locationInput); ?>)
                                </h2>

                                <img src="<?= htmlspecialchars($icon); ?>" alt="Weather Condition" class="w-12" />
                            </div>
                            <ul>
                                <li>
                                    <div class="">
                                        <span>Temperature (c): <?= htmlspecialchars($tempC); ?></span>
                                        <span> | </span>
                                        <span>Feels Like (c): <?= htmlspecialchars($tempCFeelsLike); ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="">
                                        <span>Temperature (f): <?= htmlspecialchars($tempF); ?></span>
                                        <span> | </span>
                                        <span>Feels Like (f): <?= htmlspecialchars($tempFFeelsLike); ?></span>
                                    </div>
                                </li>
                                <li>Wind Speed (mph): <?= htmlspecialchars($windMph); ?></li>
                                <li>Uv: <?= htmlspecialchars($uv); ?></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-sm text-neutral-500">No Valid Data, Input something!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>



        <!-- Toasts -->

    </main>

        <?php require_once 'components/toast.php'; ?>
        <?php require_once 'components/footer.php'; ?>
</body>

</html>