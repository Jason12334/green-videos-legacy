<?php
include('config.php');
include('header.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);


    if ($user && password_verify($password, $user['password'])) {
        

        if ($user['status'] == 'banned') {
            echo "<p style='color: red;'>Your account has been banned. Please contact support.</p>";
        } elseif ($user['status'] == 'disabled') {
            echo "<p style='color: red;'>Your account is currently disabled. Please contact support.</p>";
        } else {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];  
            $_SESSION['is_admin'] = $user['is_admin'];  


            $user_id = $user['id'];
            $log_sql = "INSERT INTO logs (user_id, username, action) VALUES ('$user_id', '$username', 'Logged in')";
            mysqli_query($conn, $log_sql);

            header("Location: home.php");
            exit;
        }
    } else {
        echo "<p style='color: red;'>Invalid credentials!</p>";
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

    .login-form {
        max-width: 400px;
        margin: 100px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .login-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .login-form button {
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

    .login-form button:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        margin-top: 10px;
        text-align: center;
    }
</style>

<div class="login-form">
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>
</div>

<?php require('footer.php'); ?>
