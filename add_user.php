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
    $dob      = $_POST['dob'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (fullname, dob, username, password) VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($conn, $query, array($fullname, $dob, $username, $password));

    if ($result) {
        header("Location: user_report.php"); // ✅ redirect back
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
  <title>Add User</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    /* ===== Page Styling (Same as add_instructor.php) ===== */
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: linear-gradient(120deg, #f4f0ff, #f0f9ff);
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      animation: fadeIn 0.6s ease-in-out;
    }

    .form-card {
      background: #fff;
      padding: 35px 40px;
      border-radius: 16px;
      box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
      width: 400px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 35px rgba(109, 40, 210, 0.2);
    }

    .form-card h2 {
      margin-bottom: 25px;
      color: #6D28D2;
      font-size: 26px;
      font-weight: 700;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
    }

    .form-card label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-weight: 600;
      color: #333;
    }

    .form-card input {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1.5px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      background: #fafafa;
      transition: 0.3s;
    }

    .form-card input:focus {
      border-color: #A435F0;
      box-shadow: 0px 0px 8px rgba(164, 53, 240, 0.3);
      outline: none;
      background: #fff;
    }

    .form-card button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #A435F0, #6D28D2);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
      letter-spacing: 0.3px;
    }

    .form-card button:hover {
      transform: scale(1.05);
      background: linear-gradient(135deg, #8e2de2, #6d28d2);
      box-shadow: 0 4px 12px rgba(109,40,210,0.3);
    }

    .back-link {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #6D28D2;
      font-weight: 600;
      transition: 0.3s;
    }

    .back-link:hover {
      color: #A435F0;
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
    <h2>Add New User</h2>
    <form method="post">
      <label>Full Name:</label>
      <input type="text" name="fullname" placeholder="Enter full name" required>

      <label>Date of Birth:</label>
      <input type="date" name="dob" required>

      <label>Username:</label>
      <input type="text" name="username" placeholder="Enter username" required>

      <label>Password:</label>
      <input type="password" name="password" placeholder="Enter password" required>

      <button type="submit">➕ Add User</button>
    </form>
    <a href="user_report.php" class="back-link">⬅ Back to User Report</a>
  </div>
</div>

</body>
</html>
