<?php
include('config.php');
require('header.php');
$featured_sql = "SELECT * FROM videos LIMIT 1";
$featured_result = mysqli_query($conn, $featured_sql);
$featured_video = mysqli_fetch_assoc($featured_result);
$hot_videos_sql = "SELECT * FROM videos ORDER BY views DESC LIMIT 8";
$hot_videos_result = mysqli_query($conn, $hot_videos_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Video Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Featured Video and Video Grid Section -->
    <section class="main-content">
        <!-- Video Grid -->
        <div class="video-grid">
            <h2>Hot Videos</h2>
            <div class="videos-list">
                <?php while ($row = mysqli_fetch_assoc($hot_videos_result)) { ?>
                    <div class="video-item">
                        <a href="watch.php?id=<?php echo $row['id']; ?>">
                            <!-- Dynamically display the thumbnail -->
                            <img src="<?php echo $row['thumbnail_path']; ?>" alt="<?php echo $row['video_name']; ?>" class="video-thumbnail">
                            <div class="video-info">
                                <p><?php echo $row['video_name']; ?></p>
                                <span><?php echo $row['views']; ?> views</span>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Featured Video -->
        <div class="featured-video">
            <h2>Featured Video</h2>
            <?php if ($featured_video) { ?>
                <video width="320" controls>
                    <source src="<?php echo $featured_video['video_path']; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h3><?php echo $featured_video['video_name']; ?></h3>
                <p>Views: <?php echo $featured_video['views']; ?></p>
            <?php } else { ?>
                <p>No featured video available.</p>
            <?php } ?>
        </div>
    </section>
<?php require('footer.php'); ?>
</body>
</html>