<nav class="navbar bg-base-100">
    <div class="navbar-start">
        <a href="index.php" class="btn btn-ghost btn-circle">
            <div class="w-full">
                <img src="../assets/logo.png" alt="Health Group Logo">
            </div>
        </a>
    </div>

    <div class="navbar-center">
        <ul class="menu menu-horizontal px-2">
            <li><a href="index.php">Home</a></li>
            <li><a href="information-advice.php">Information & Advice</a></li>
            <li><a href="weather.php">Weather</a></li>
            <li><a href="air-quality.php">Air Quality</a></li>
            <li><a href="risk-assessment.php">Risk Assessment</a></li>
        </ul>
    </div>

    <?php if (isset($_SESSION['user-logged-in'])): ?>
        <div class="navbar-end space-x-2">
            <?php if (isset($_SESSION['user-admin']) && $_SESSION['user-admin'] === true): ?>
                <a href="admin.php" class="btn btn-error">Admin</a>
            <?php endif; ?>
            <a href="profile.php" class="btn btn-ghost">Profile</a>
            <a href="../app/service/user-logout.php" class="btn btn-neutral">Logout</a>
        </div>
    <?php else: ?>
        <div class="navbar-end space-x-2">
            <a href="login.php" class="btn btn-ghost">Login</a>
            <a href="sign-up.php" class="btn btn-neutral">Sign Up</a>
        </div>
    <?php endif; ?>
</nav>