<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Fetch user details using session user_id
$query = "SELECT fullname, dob, username FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($_SESSION['user_id']));

if ($result && pg_num_rows($result) === 1) {
    $userData = pg_fetch_assoc($result);
} else {
    echo "Error fetching user details.";
    exit();
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($userData['fullname']); ?> 🎉</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($userData['dob']); ?></p>

    <br>
    <a href="index.php">🏠 Back to Home</a> |
    <a href="logout.php" style="color:red;">Logout</a>
</body>
</html>
