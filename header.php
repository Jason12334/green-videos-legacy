<?php
session_start();
include('config.php');
$maintenance_sql = "SELECT maintenance_mode FROM settings LIMIT 1";
$maintenance_result = mysqli_query($conn, $maintenance_sql);
$maintenance = mysqli_fetch_assoc($maintenance_result);
if ($maintenance['maintenance_mode'] == 1 && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1)) {
    header("Location: maintenance.php");
    exit;
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $status_check_sql = "SELECT status FROM users WHERE id = '$user_id'";
    $status_check_result = mysqli_query($conn, $status_check_sql);
    $user_status = mysqli_fetch_assoc($status_check_result);
    if ($user_status && $user_status['status'] == 'banned') {
        session_destroy();
        header("Location: login.php?message=Your account has been banned.");
        exit;
    }
}
$alert_sql = "SELECT message FROM alerts WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1";
$alert_result = mysqli_query($conn, $alert_sql);
$alert = mysqli_fetch_assoc($alert_result);
$tos_accepted = isset($_COOKIE['tos_accepted']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="home.php"><img src="logo.png" alt="Logo" class="logo-img"></a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="my.php">My Video Website</a></li>
                <li><a href="home.php">Home</a></li>
                <li><a href="videos.php">Videos</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="upload.php">Upload</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) { ?>
                    <li><a href="admin_dashboard.php">Admin Panel</a></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="auth-btn">Logout</a>
            <?php } else { ?>
                <a href="signup.php" class="auth-btn">Sign Up</a> or
                <a href="login.php" class="auth-btn">Login</a>
            <?php } ?>
        </div>
    </header>
    <!-- Display Alert Below Header -->
    <?php if ($alert) { ?>
        <div class="alert-banner">
            <?php echo htmlspecialchars($alert['message']); ?>
        </div>
    <?php } ?>
    <!-- TOS Popup (Only shown if the user hasn't accepted the TOS) -->
    <?php if (!$tos_accepted) { ?>
        <div id="tos-popup" class="tos-popup">
            <div class="tos-content">
                <h2>Terms of Service</h2>
                <p>By using this site, you agree to our <a href="tos.html">Terms of Service</a>.</p>
                <button id="accept-tos">Accept</button>
            </div>
        </div>
    <?php } ?>
    <script>
        document.getElementById('accept-tos').addEventListener('click', function() {
            document.cookie = "tos_accepted=true; path=/; max-age=" + 60*60*24*365; 
            document.getElementById('tos-popup').style.display = 'none';
        });
    </script>
</body>
</html>