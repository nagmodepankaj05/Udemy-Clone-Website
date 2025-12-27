<?php
session_start();

// ✅ Check admin session
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ DB connection
$host     = "localhost";
$port     = "5432";
$dbname   = "udemy";
$user     = "postgres";
$password = "@Pankaj0123456";
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO instructor (name, email, username, dob, password) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, array($fullname, $email, $username, $dob, $password));

    if ($result) {
        header("Location: instructor_report.php"); // ✅ redirect back
        exit();
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Instructor</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .form-card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
      width: 400px;
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }
    .form-card h2 {
      margin-bottom: 20px;
      color: #007bff;
    }
    .form-card label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }
    .form-card input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      transition: 0.3s;
    }
    .form-card input:focus {
      border-color: #007bff;
      box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.3);
      outline: none;
    }
    .form-card button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    .form-card button:hover {
      background: #0056b3;
    }
    .back-link {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      transition: 0.3s;
    }
    .back-link:hover {
      color: #0056b3;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-card">
    <h2>Add New Instructor</h2>
    <form method="post">
      <label>Full Name:</label>
      <input type="text" name="fullname" placeholder="Enter full name" required>

      <label>Email:</label>
      <input type="email" name="email" placeholder="Enter email" required>

      <label>Username:</label>
      <input type="text" name="username" placeholder="Enter username" required>

      <label>Date of Birth:</label>
      <input type="date" name="dob" required>

      <label>Password:</label>
      <input type="password" name="password" placeholder="Enter password" required>

      <button type="submit">➕ Add Instructor</button>
    </form>
    <a href="instructor_report.php" class="back-link">⬅ Back to Instructor Report</a>
  </div>
</div>

</body>
</html>
