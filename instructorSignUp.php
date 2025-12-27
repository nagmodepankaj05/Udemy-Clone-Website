<?php

$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";


$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");


if (!$conn) {
    die("Connection failed: " . pg_last_error());
}


$fullname = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$username = $_POST['username'];
$password_input = $_POST['password'];


$hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

$query = "INSERT INTO instructor (name, email, dob, username, password) VALUES ($1, $2, $3, $4, $5)";
$result = pg_query_params($conn, $query, array($fullname, $email, $dob, $username, $hashed_password));

if ($result) {
    echo "<script>alert('Sign up successful!'); window.location.href='instructorLogin.html';</script>";
} else {
    echo "Error: " . pg_last_error($conn);
}


pg_close($conn);
?>
