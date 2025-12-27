<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// PostgreSQL connection
$host     = "localhost";
$port     = "5432";
$dbname   = "udemy";
$user     = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);

    $deleteQuery = "DELETE FROM users WHERE id = $1";
    $result = pg_query_params($conn, $deleteQuery, array($userId));

    if ($result) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete user: " . pg_last_error($conn);
    }
}

pg_close($conn);

// Redirect back to admin dashboard
header("Location: admin.php");
exit();
