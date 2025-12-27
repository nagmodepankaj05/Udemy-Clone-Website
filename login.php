<?php
session_start();

$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pass = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = $1";
    $result = pg_query_params($conn, $query, array($username));

    if (pg_num_rows($result) === 1) {
        $userData = pg_fetch_assoc($result);

        if (password_verify($pass, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            echo "<script>alert('Login successful!'); window.location.href='userLogin.php';</script>";
            exit();
        } else {
            echo "<p style='color:red;'>Invalid password</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid username</p>";
    }
}

pg_close($conn);
?>
