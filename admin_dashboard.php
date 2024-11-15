<?php
include('config.php');
include('header.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo "You are not authorized to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-dashboard-container">
        <h1>Admin Dashboard</h1>
        <div class="admin-sections">
            <div class="admin-section">
                <h2>Manage Users</h2>
                <p>View, ban, disable, or enable users.</p>
                <a href="admin.php" class="admin-link-btn">Go to User Management</a>
            </div>
            <div class="admin-section">
                <h2>Manage Alerts</h2>
                <p>Create or deactivate alerts that are displayed on the site.</p>
                <a href="admin_alerts.php" class="admin-link-btn">Go to Alert Management</a>
            </div>
            <div class="admin-section">
                <h2>View Logs</h2>
                <p>See user activity, errors, or other logs related to site usage.</p>
                <a href="logs.php" class="admin-link-btn">View Logs</a>
            </div>
            <div class="admin-section">
                <h2>Site Settings</h2>
                <p>Modify general site settings, such as maintenance mode or contact information.</p>
                <a href="site_settings.php" class="admin-link-btn">Go to Site Settings</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php include('footer.php'); ?>