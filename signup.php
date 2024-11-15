<?php
include('config.php');
require('header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $signup_key = $_POST['signup_key'];


    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {

        $check_key_sql = "SELECT * FROM signup_keys WHERE key_value = '$signup_key' AND is_used = 0 LIMIT 1";
        $key_result = mysqli_query($conn, $check_key_sql);
        $key_row = mysqli_fetch_assoc($key_result);

        if (!$key_row) {
            echo "Invalid or already used signup key!";
        } else {

            $check_username_sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $check_username_sql);

            if (mysqli_num_rows($result) > 0) {
                echo "Username is already taken. Please choose a different one.";
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);


                $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

                if (mysqli_query($conn, $sql)) {

                    $update_key_sql = "UPDATE signup_keys SET is_used = 1 WHERE id = " . $key_row['id'];
                    mysqli_query($conn, $update_key_sql);

                    echo "Signup successful! <a href='login.php'>Login</a>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
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

    .signup-form {
        max-width: 400px;
        margin: 100px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .signup-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .signup-form input[type="text"],
    .signup-form input[type="password"],
    .signup-form input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .signup-form button {
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

    .signup-form button:hover {
        background-color: #45a049;
    }
</style>


<div class="signup-form">
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <label for="signup_key">Signup Key:</label>
        <input type="text" name="signup_key" id="signup_key" required>

        <button type="submit">Sign Up</button>
    </form>
</div>

<?php require('footer.php'); ?>
