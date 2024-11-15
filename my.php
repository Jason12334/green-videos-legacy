<?php
include('config.php');
include('header.php');
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$videos_sql = "SELECT * FROM videos WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 24";
$videos_result = mysqli_query($conn, $videos_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Videos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="videos-container">
        <h1>My Uploaded Videos</h1>


        <div class="videos-list">
            <?php if (mysqli_num_rows($videos_result) > 0) { ?>
                <?php while ($video = mysqli_fetch_assoc($videos_result)) { ?>
                    <div class="video-item">
                        <a href="watch.php?id=<?php echo $video['id']; ?>">
                            <img src="<?php echo htmlspecialchars($video['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($video['video_name']); ?>" class="video-thumbnail">
                            <div class="video-info">
                                <p><?php echo htmlspecialchars($video['video_name']); ?></p>
                                <p><?php echo $video['views']; ?> views</p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>You haven't uploaded any videos yet.</p>
            <?php } ?>
        </div>
    </div>

<?php include('footer.php'); ?>
</body>
</html>
