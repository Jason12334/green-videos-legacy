<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $video_id = $_POST['video_id'];
    $comment = $_POST['comment'];
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';

    $sql = "INSERT INTO comments (video_id, username, comment) VALUES ('$video_id', '$username', '$comment')";
    if (mysqli_query($conn, $sql)) {
        header("Location: watch.php?id=$video_id");
        exit;
    } else {
        echo "Error posting comment: " . mysqli_error($conn);
    }
}
?>
