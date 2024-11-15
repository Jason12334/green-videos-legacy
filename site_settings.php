<?php
include('config.php');
include('header.php');
session_start();


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "You are not authorized to access this page.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maintenance_mode = $_POST['maintenance_mode'];


    $update_sql = "UPDATE settings SET maintenance_mode = '$maintenance_mode' WHERE id = 1";
    if (mysqli_query($conn, $update_sql)) {
        echo "Maintenance mode updated successfully.";
    } else {
        echo "Error updating maintenance mode: " . mysqli_error($conn);
    }
}


$maintenance_sql = "SELECT maintenance_mode FROM settings WHERE id = 1";
$maintenance_result = mysqli_query($conn, $maintenance_sql);
$maintenance = mysqli_fetch_assoc($maintenance_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Site Settings</title>
</head>
<body>
    <h1>Site Settings</h1>


    <form method="POST">
        <label for="maintenance_mode">Maintenance Mode:</label><br>
        <select name="maintenance_mode" id="maintenance_mode">
            <option value="0" <?php if ($maintenance['maintenance_mode'] == 0) echo 'selected'; ?>>Disabled</option>
            <option value="1" <?php if ($maintenance['maintenance_mode'] == 1) echo 'selected'; ?>>Enabled</option>
        </select><br><br>
        <button type="submit">Update Settings</button>
    </form>
</body>
</html>
<?php include('footer.php'); ?>