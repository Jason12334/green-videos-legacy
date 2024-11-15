<?php
include('config.php');
include('header.php');
session_start();
$username = $_GET['username'];
$user_sql = "SELECT * FROM users WHERE username='$username'";
$user_result = mysqli_query($conn, $user_sql);
if (mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
} else {
    echo "User not found!";
    exit; 
}
if (empty($user)) {
    echo "No user data available.";
    exit; 
}
$total_views_sql = "SELECT SUM(views) AS total_views FROM videos WHERE user_id='" . $user['id'] . "'";
$total_views_result = mysqli_query($conn, $total_views_sql);
$total_views_row = mysqli_fetch_assoc($total_views_result);
$total_views = $total_views_row['total_views'] ?? 0;  
$follower_count_sql = "SELECT COUNT(*) AS follower_count FROM follows WHERE followed_id='" . $user['id'] . "'";
$follower_count_result = mysqli_query($conn, $follower_count_sql);
$follower_count_row = mysqli_fetch_assoc($follower_count_result);
$follower_count = $follower_count_row['follower_count'] ?? 0;  
$videos_sql = "SELECT * FROM videos WHERE user_id='" . $user['id'] . "' ORDER BY created_at DESC";
$videos_result = mysqli_query($conn, $videos_sql);
$feed_sql = "SELECT * FROM videos WHERE user_id='" . $user['id'] . "' ORDER BY created_at DESC LIMIT 5";
$feed_result = mysqli_query($conn, $feed_sql);
$featured_video_sql = "SELECT * FROM videos WHERE user_id='" . $user['id'] . "' AND is_featured=1 LIMIT 1";
$featured_video_result = mysqli_query($conn, $featured_video_sql);
$featured_video = mysqli_fetch_assoc($featured_video_result);
$is_owner = isset($_SESSION['username']) && $_SESSION['username'] === $user['username'];
$logged_in_user_id = $_SESSION['user_id'] ?? null;
if ($logged_in_user_id && !$is_owner) {
    $check_follow_sql = "SELECT * FROM follows WHERE follower_id='$logged_in_user_id' AND followed_id='" . $user['id'] . "'";
    $check_follow_result = mysqli_query($conn, $check_follow_sql);
    $is_following = mysqli_num_rows($check_follow_result) > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Channel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="channel-content">
    <!-- Channel Header -->
    <div class="channel-header">
        <h1><?php echo htmlspecialchars($user['username']); ?>'s Channel</h1>
        <div class="user-details">
            <p>Joined: <?php echo isset($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : 'Unknown'; ?></p>
            <p>Subscribers: <?php echo $follower_count; ?></p> <!-- Display actual follower count -->
            <p>Total Views: <?php echo $total_views; ?></p> <!-- Display total views dynamically -->
        </div>
        <!-- Display follow/unfollow button if the viewer is not the channel owner -->
        <?php if (!$is_owner && $logged_in_user_id) { ?>
            <?php if ($is_following) { ?>
                <form method="POST" action="unfollow.php">
                    <input type="hidden" name="followed_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <button type="submit">Unfollow</button>
                </form>
            <?php } else { ?>
                <form method="POST" action="follow.php">
                    <input type="hidden" name="followed_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <button type="submit">Follow</button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
    <!-- Featured Video Section -->
    <div class="featured-video">
        <?php if ($is_owner) { ?>
            <h2>Add a featured video</h2>
            <form method="POST" action="add_featured_video.php">
                <label for="featured_video">Select a video to feature:</label>
                <select name="featured_video_id" id="featured_video">
                    <?php
                    mysqli_data_seek($videos_result, 0);
                    while ($video = mysqli_fetch_assoc($videos_result)) { ?>
                        <option value="<?php echo htmlspecialchars($video['id']); ?>"><?php echo htmlspecialchars($video['video_name']); ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Feature Video</button>
            </form>
        <?php } else { ?>
            <?php if ($featured_video) { ?>
                <h2>Featured Video</h2>
                <video width="640" controls>
                    <source src="<?php echo htmlspecialchars($featured_video['video_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h3><?php echo htmlspecialchars($featured_video['video_name']); ?></h3>
                <p><?php echo $featured_video['views']; ?> views</p>
            <?php } else { ?>
                <p>No featured video available.</p>
            <?php } ?>
        <?php } ?>
    </div>
    <!-- Uploaded Videos Section -->
    <div class="uploaded-videos">
        <h2>Uploaded Videos</h2>
        <div class="videos-list">
            <?php
            mysqli_data_seek($videos_result, 0);
            while ($video = mysqli_fetch_assoc($videos_result)) { ?>
                <div class="video-item">
                    <a href="watch.php?id=<?php echo htmlspecialchars($video['id']); ?>">
                        <img src="<?php echo htmlspecialchars($video['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($video['video_name']); ?>" class="video-thumbnail">
                        <div class="video-info">
                            <p><?php echo htmlspecialchars($video['video_name']); ?></p>
                            <p><?php echo $video['views']; ?> views</p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- User Feed Section -->
    <div class="user-feed">
        <h2><?php echo htmlspecialchars($user['username']); ?>'s Feed</h2>
        <ul>
            <?php while ($feed = mysqli_fetch_assoc($feed_result)) { ?>
                <li><?php echo htmlspecialchars($feed['video_name']); ?> - Uploaded on <?php echo date("F j, Y", strtotime($feed['created_at'])); ?></li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php include('footer.php'); ?>
</body>
</html>