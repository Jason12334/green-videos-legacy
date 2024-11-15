<?php
session_start();
include('config.php');


if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $log_sql = "INSERT INTO logs (user_id, username, action) VALUES ('$user_id', '$username', 'Logged out')";
    mysqli_query($conn, $log_sql);
}


session_destroy();


header("Location: index.php");
exit;
?>

