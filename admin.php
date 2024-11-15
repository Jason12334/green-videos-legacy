<?php
include('config.php');
include('header.php');
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo "You are not authorized to access this page.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'ban') {
        $update_sql = "UPDATE users SET status = 'banned' WHERE id = '$user_id'";
    } elseif ($action == 'unban') {
        $update_sql = "UPDATE users SET status = 'active' WHERE id = '$user_id'";
    } elseif ($action == 'disable') {
        $update_sql = "UPDATE users SET status = 'disabled' WHERE id = '$user_id'";
    } elseif ($action == 'enable') {
        $update_sql = "UPDATE users SET status = 'active' WHERE id = '$user_id'";
    } elseif ($action == 'delete_video') {
        $video_id = $_POST['video_id'];
        $update_sql = "DELETE FROM videos WHERE id = '$video_id'";
    }

    if (mysqli_query($conn, $update_sql)) {
        echo "Action completed successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


$users_sql = "
    SELECT u.id AS user_id, u.username, u.email, u.status, v.id AS video_id, v.video_name 
    FROM users u 
    LEFT JOIN videos v ON u.id = v.user_id
    ORDER BY u.username ASC
";
$users_result = mysqli_query($conn, $users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Admin Panel</h1>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Video Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                        <td><?php echo $user['video_name'] ? htmlspecialchars($user['video_name']) : 'No Videos'; ?></td>
                        <td>

                            <?php if ($user['status'] == 'banned') { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="action" value="unban">
                                    <button type="submit" class="btn">Unban</button>
                                </form>
                            <?php } else { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="action" value="ban">
                                    <button type="submit" class="btn">Ban</button>
                                </form>
                            <?php } ?>


                            <?php if ($user['status'] == 'disabled') { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="action" value="enable">
                                    <button type="submit" class="btn">Enable</button>
                                </form>
                            <?php } else { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="action" value="disable">
                                    <button type="submit" class="btn">Disable</button>
                                </form>
                            <?php } ?>


                            <?php if ($user['video_id']) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="video_id" value="<?php echo $user['video_id']; ?>">
                                    <input type="hidden" name="action" value="delete_video">
                                    <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete this video?')">Delete Video</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
