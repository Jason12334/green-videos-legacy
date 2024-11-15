<?php
include('config.php');
require('header.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM videos WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);
?>

<h1>Your Videos</h1>
<a href="logout.php">Logout</a> | <a href="upload.php">Upload Video</a>

<ul>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <li><?php echo $row['video_name']; ?> - <a href="<?php echo $row['video_path']; ?>">Watch</a></li>
<?php } ?>
</ul>
