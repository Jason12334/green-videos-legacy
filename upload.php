<?php
include('config.php');
require('header.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $video_dir = "uploads/videos/";
    $video_file = $video_dir . basename($_FILES["video"]["name"]);
    $videoFileType = strtolower(pathinfo($video_file, PATHINFO_EXTENSION));


    $thumbnail_dir = "uploads/thumbnails/";
    $thumbnail_file = $thumbnail_dir . basename($_FILES["thumbnail"]["name"]);
    $thumbnailFileType = strtolower(pathinfo($thumbnail_file, PATHINFO_EXTENSION));


    $allowed_video_types = ['mp4', 'avi', 'mov', 'wmv'];
    $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($videoFileType, $allowed_video_types)) {
        echo "Only video files (MP4, AVI, MOV, WMV) are allowed!";
        exit;
    }

    if (!in_array($thumbnailFileType, $allowed_image_types)) {
        echo "Only image files (JPG, PNG, GIF) are allowed for the thumbnail!";
        exit;
    }


    if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_file) && move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail_file)) {
        $video_name = $_POST['video_name'];
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username']; 
        

        $sql = "INSERT INTO videos (user_id, video_name, video_path, thumbnail_path, views) VALUES ('$user_id', '$video_name', '$video_file', '$thumbnail_file', 0)";
        
        if (mysqli_query($conn, $sql)) {
            echo "Video and thumbnail uploaded successfully!";
            

            $log_sql = "INSERT INTO logs (user_id, username, action) VALUES ('$user_id', '$username', 'Uploaded a video: $video_name')";
            mysqli_query($conn, $log_sql);
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    } else {
        echo "There was an error uploading the video or thumbnail.";
    }
}
?>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .upload-form {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .upload-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .upload-form input[type="text"],
    .upload-form input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .upload-form button {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .upload-form button:hover {
        background-color: #45a049;
    }
</style>


<div class="upload-form">
    <form method="POST" enctype="multipart/form-data">
        <label for="video_name">Video Name:</label>
        <input type="text" name="video_name" id="video_name" required>

        <label for="video">Select video to upload:</label>
        <input type="file" name="video" id="video" required>

        <label for="thumbnail">Select thumbnail image to upload:</label>
        <input type="file" name="thumbnail" id="thumbnail" required>

        <button type="submit">Upload Video</button>
    </form>
</div>

<?php require('footer.php'); ?>
