<?php
include('config.php');
include('header.php');
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo "You are not authorized to access this page.";
    exit;
}


$logs_sql = "SELECT * FROM logs ORDER BY action_time DESC";
$logs_result = mysqli_query($conn, $logs_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h1>User Activity Logs</h1>
        
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Action</th>
                    <th>Action Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($log = mysqli_fetch_assoc($logs_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['username']); ?></td>
                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                        <td><?php echo htmlspecialchars($log['action_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
