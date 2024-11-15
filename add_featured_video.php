<?php
include('config.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $featured_video_id = $_POST['featured_video_id'];
    $username = $_SESSION['username'];
    $user_sql = "SELECT id FROM users WHERE username='$username'";
    $user_result = mysqli_query($conn, $user_sql);
    $user = mysqli_fetch_assoc($user_result);
    $user_id = $user['id'];
    $reset_featured_sql = "UPDATE videos SET is_featured = 0 WHERE user_id='$user_id'";
    mysqli_query($conn, $reset_featured_sql);
    $set_featured_sql = "UPDATE videos SET is_featured = 1 WHERE id='$featured_video_id' AND user_id='$user_id'";
    if (mysqli_query($conn, $set_featured_sql)) {
        echo "Featured video updated successfully!";
        header("Location: channel.php?username=" . $username);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>