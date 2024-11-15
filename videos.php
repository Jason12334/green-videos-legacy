<?php
include('config.php');
include('header.php');

session_start();


$videos_per_page = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $videos_per_page; 


$total_videos_sql = "SELECT COUNT(*) as total FROM videos";
$total_videos_result = mysqli_query($conn, $total_videos_sql);
$total_videos_row = mysqli_fetch_assoc($total_videos_result);
$total_videos = $total_videos_row['total'];


$total_pages = ceil($total_videos / $videos_per_page);


$videos_sql = "SELECT * FROM videos LIMIT $videos_per_page OFFSET $offset";
$videos_result = mysqli_query($conn, $videos_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Videos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="content">

        <div class="video-grid">
            <h2>All Videos</h2>
            <div class="videos-list">
                <?php while ($video = mysqli_fetch_assoc($videos_result)) { ?>
                    <div class="video-item">
                        <a href="watch.php?id=<?php echo $video['id']; ?>">
                            <img src="<?php echo $video['thumbnail_path']; ?>" alt="<?php echo $video['video_name']; ?>" class="video-thumbnail">
                            <div class="video-info">
                                <p><?php echo $video['video_name']; ?></p>
                                <span><?php echo $video['views']; ?> views</span>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>


        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="videos.php?page=<?php echo $page - 1; ?>" class="pagination-btn">Previous</a>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="videos.php?page=<?php echo $i; ?>" class="pagination-btn <?php if ($i == $page) echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>

            <?php if ($page < $total_pages) { ?>
                <a href="videos.php?page=<?php echo $page + 1; ?>" class="pagination-btn">Next</a>
            <?php } ?>
        </div>
    </div>
<?php require('footer.php'); ?>
</body>
</html>
