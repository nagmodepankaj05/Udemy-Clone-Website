<?php
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$id = $_GET['id'];

// delete course
$query = "DELETE FROM courses WHERE id=$1";
$result = pg_query_params($conn, $query, [$id]);

if ($result) {
    echo "<script>alert('Course Deleted Successfully!'); window.location.href='courses.php';</script>";
} else {
    echo "Error: " . pg_last_error($conn);
}
?>
