<?php
$servername = "host";
$username = "username";
$password = "pw";
$dbname = "dbname";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>



