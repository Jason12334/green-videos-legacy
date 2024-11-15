<?php
include('config.php');
include('header.php');
session_start();


$video_id = $_GET['id'];


$sql = "SELECT videos.*, users.username FROM videos 
        JOIN users ON videos.user_id = users.id 
        WHERE videos.id='$video_id'";
$result = mysqli_query($conn, $sql);
$video = mysqli_fetch_assoc($result);


$update_views_sql = "UPDATE videos SET views = views + 1 WHERE id='$video_id'";
mysqli_query($conn, $update_views_sql);


$related_videos_sql = "SELECT * FROM videos WHERE id != '$video_id' ORDER BY RAND() LIMIT 5";
$related_videos_result = mysqli_query($conn, $related_videos_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video['video_name']; ?> - Video</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


    <div class="content">

        <div class="video-section">
            <h1><?php echo $video['video_name']; ?></h1>
            <video width="640" controls>
                <source src="<?php echo $video['video_path']; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-info">
                <p>Views: <?php echo $video['views']; ?></p>
                <p>Uploaded on: <?php echo date("F j, Y", strtotime($video['created_at'])); ?></p>
                <p><?php echo $video['description']; ?></p>
                <p>Uploaded by: <a href="channel.php?username=<?php echo $video['username']; ?>"><?php echo $video['username']; ?></a></p>
            </div>


            <div class="comments-section">
                <h2>Comments</h2>
                <form method="POST" action="post_comment.php">
                    <textarea name="comment" required placeholder="Add a comment"></textarea><br>
                    <input type="hidden" name="video_id" value="<?php echo $video_id; ?>">
                    <button type="submit">Post Comment</button>
                </form>


                <?php
                $comments_sql = "SELECT * FROM comments WHERE video_id='$video_id' ORDER BY created_at DESC";
                $comments_result = mysqli_query($conn, $comments_sql);
                while ($comment = mysqli_fetch_assoc($comments_result)) {
                    echo "<div class='comment'><strong>{$comment['username']}</strong>: {$comment['comment']}<br><small>Posted on: " . date("F j, Y", strtotime($comment['created_at'])) . "</small></div>";
                }
                ?>
            </div>
        </div>


        <div class="related-videos">
            <h3>Related Videos</h3>
            <?php while ($related = mysqli_fetch_assoc($related_videos_result)) { ?>
                <div class="related-item">
                    <a href="watch.php?id=<?php echo $related['id']; ?>">
                        <img src="<?php echo $related['thumbnail_path']; ?>" alt="<?php echo $related['video_name']; ?>" class="related-thumbnail">
                        <p><?php echo $related['video_name']; ?></p>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php require('footer.php'); ?>
</body>
</html>
