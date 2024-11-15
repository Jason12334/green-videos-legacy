<?php
include('config.php');
include('header.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo "You are not authorized to access this page.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'] ?? null;
    $action = $_POST['action'];
    $alert_id = $_POST['alert_id'] ?? null;
    if ($action == 'add') {
        $add_alert_sql = "INSERT INTO alerts (message, is_active) VALUES ('$message', 1)";
        mysqli_query($conn, $add_alert_sql);
    } elseif ($action == 'deactivate') {
        $deactivate_sql = "UPDATE alerts SET is_active = 0 WHERE id = '$alert_id'";
        mysqli_query($conn, $deactivate_sql);
    } elseif ($action == 'reactivate') {
        $reactivate_sql = "UPDATE alerts SET is_active = 1 WHERE id = '$alert_id'";
        mysqli_query($conn, $reactivate_sql);
    }
}
$alerts_sql = "SELECT * FROM alerts ORDER BY created_at DESC";
$alerts_result = mysqli_query($conn, $alerts_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Alerts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Alerts</h1>
        <!-- Form to add a new alert -->
        <form method="POST">
            <label for="message">Alert Message:</label><br>
            <textarea name="message" id="message" rows="4" required></textarea><br><br>
            <input type="hidden" name="action" value="add">
            <button type="submit">Add Alert</button>
        </form>
        <hr>
        <!-- Display existing alerts -->
        <h2>Manage Existing Alerts</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($alert = mysqli_fetch_assoc($alerts_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alert['message']); ?></td>
                        <td><?php echo $alert['is_active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <!-- Deactivate button if the alert is active -->
                            <?php if ($alert['is_active']) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="alert_id" value="<?php echo $alert['id']; ?>">
                                    <input type="hidden" name="action" value="deactivate">
                                    <button type="submit">Deactivate</button>
                                </form>
                            <?php } ?>
                            <!-- Reactivate button if the alert is inactive -->
                            <?php if (!$alert['is_active']) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="alert_id" value="<?php echo $alert['id']; ?>">
                                    <input type="hidden" name="action" value="reactivate">
                                    <button type="submit">Reactivate</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php include('footer.php'); ?>