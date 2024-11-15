<?php
include('config.php');
include('header.php');


$users_sql = "
    SELECT 
        u.id, u.username, 
        (SELECT COUNT(*) FROM follows WHERE followed_id = u.id) AS followers,
        (SELECT SUM(views) FROM videos WHERE user_id = u.id) AS total_views
    FROM users u
";
$users_result = mysqli_query($conn, $users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Video Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-content user-list-page">
    <div class="user-list">
        <h2>All Users</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Followers</th>
                    <th>Total Views</th>
                    <th>Channel Link</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo $user['followers'] ?? 0; ?></td>
                        <td><?php echo $user['total_views'] ?? 0; ?></td>
                        <td>
                            <a href="channel.php?username=<?php echo htmlspecialchars($user['username']); ?>" class="channel-link">View Channel</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
