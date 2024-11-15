<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$follower_id = $_SESSION['user_id'];
$followed_id = $_POST['followed_id'];

$sql = "INSERT INTO follows (follower_id, followed_id) VALUES ('$follower_id', '$followed_id')";
if (mysqli_query($conn, $sql)) {
    header('Location: index.php');
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
