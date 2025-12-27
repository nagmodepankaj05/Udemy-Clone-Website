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

// ✅ Get total instructors count
$instructorCountResult = pg_query($conn, "SELECT COUNT(*) AS total_instructors FROM instructor");
$instructorCount = pg_fetch_result($instructorCountResult, 0, 'total_instructors');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Instructor Report</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    .dashboard-row {
      display: flex;
      gap: 20px;
      margin: 20px;
    }
    .dashboard-card {
      flex: 1;
      background: #007bff;
      color: white;
      text-align: center;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .dashboard-card h3 {
      margin: 0 0 10px 0;
    }
    .dashboard-card a {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 15px;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .dashboard-card a:hover {
      background: #218838;
    }
    .search-bar {
      margin: 20px;
      text-align: center;
    }
    .search-bar input {
      width: 50%;
      padding: 10px;
      font-size: 16px;
    }
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background: #007bff;
      color: white;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <div class="navbar-left">
      <h1>Admin Dashboard</h1>
      <div class="navbar-menu">
          <a href="user_report.php">Users</a>
          <a href="instructor_report.php">Instructors</a>
          <a href="course_report.php">Courses</a>
      </div>
  </div>
</div>

<!-- ✅ Row with two cards -->
<div class="dashboard-row">
  <div class="dashboard-card">
    <h3>Total Instructors</h3>
    <p><?= $instructorCount ?></p>
  </div>
  <div class="dashboard-card">
    <h3>Add New Instructor</h3>
    <a href="add_instructor.php">Add Instructor</a>
  </div>
</div>

<!-- ✅ Search bar -->
<div class="search-bar">
  <input type="text" id="searchInput" placeholder="Search instructors..." onkeyup="searchInstructors()">
</div>

<!-- ✅ Instructors Table -->
<h2 style="margin-left:20px;">All Instructors</h2>
<table id="instructorTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Username</th>
      <th>DOB</th>
      <th>Delete Instructor</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $instructorQuery = "SELECT id, name, email, username, dob FROM instructor ORDER BY id ASC";
      $instructorResult = pg_query($conn, $instructorQuery);
      if ($instructorResult) {
          while ($row = pg_fetch_assoc($instructorResult)) {
              echo "<tr>";
              echo "<td>".htmlspecialchars($row['id'])."</td>";
              echo "<td>".htmlspecialchars($row['name'])."</td>";
              echo "<td>".htmlspecialchars($row['email'])."</td>";
              echo "<td>".htmlspecialchars($row['username'])."</td>";
              echo "<td>".htmlspecialchars($row['dob'])."</td>";
              echo "<td>
                      <form method='POST' action='delete_user.php' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                        <button type='submit' style='background:red;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;'>Delete</button>
                      </form>
                    </td>";
              echo "</tr>";
          }
      }
    ?>
  </tbody>
</table>

<script>
function searchInstructors() {
  let input = document.getElementById("searchInput").value.toLowerCase();
  let table = document.getElementById("instructorTable");
  let tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    let tdArray = tr[i].getElementsByTagName("td");
    let found = false;
    for (let j = 0; j < tdArray.length; j++) {
      let cell = tdArray[j];
      if (cell) {
        let textValue = cell.textContent || cell.innerText;
        if (textValue.toLowerCase().indexOf(input) > -1) {
          found = true;
          break;
        }
      }
    }
    tr[i].style.display = found ? "" : "none";
  }
}
</script>

<?php pg_close($conn); ?>
</body>
</html>
