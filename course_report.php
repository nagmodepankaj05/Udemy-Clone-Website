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

// ✅ Get total courses count
$courseCountResult = pg_query($conn, "SELECT COUNT(*) AS total_courses FROM courses");
$courseCount = pg_fetch_result($courseCountResult, 0, 'total_courses');

// ✅ Get total categories count
$categoryCountResult = pg_query($conn, "SELECT COUNT(*) AS total_categories FROM categories");
$categoryCount = pg_fetch_result($categoryCountResult, 0, 'total_categories');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Course Report</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    .dashboard-center {
      display: flex;
      justify-content: center;
      gap: 30px; /* ✅ Added space between boxes */
      margin: 30px 0;
    }
    .dashboard-card {
      background: #17a2b8;
      color: white;
      text-align: center;
      padding: 30px;
      border-radius: 12px;
      width: 300px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: 0.3s;
      cursor: pointer;
    }
    .dashboard-card:hover {
      background: #138496;
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
      width: 95%;
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
      background: #17a2b8;
      color: white;
    }
    td img {
      width: 80px;
      height: 60px;
      border-radius: 5px;
      object-fit: cover;
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

<!-- ✅ Center Boxes -->
<div class="dashboard-center">
  <div class="dashboard-card">
    <h3>Total Courses</h3>
    <p style="font-size:22px; font-weight:bold;"><?= $courseCount ?></p>
  </div>

  <!-- ✅ NEW BOX: Manage Categories -->
  <div class="dashboard-card" onclick="window.location.href='manage_category.php'">
    <h3>Manage Categories</h3>
    <p style="font-size:22px; font-weight:bold;"><?= $categoryCount ?></p>
  </div>
</div>

<!-- ✅ Search bar -->
<div class="search-bar">
  <input type="text" id="searchInput" placeholder="Search courses..." onkeyup="searchCourses()">
</div>

<!-- ✅ Courses Table -->
<h2 style="margin-left:30px;">All Courses</h2>
<table id="courseTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Thumbnail</th>
      <th>Title</th>
      <th>Description</th>
      <th>Category</th>
      <th>Author</th>
      <th>Created At</th>
      <th>Price</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $courseQuery = "SELECT id, title, description, category, thumbnail, created_at, author, price FROM courses ORDER BY id ASC";
      $courseResult = pg_query($conn, $courseQuery);
      if ($courseResult) {
          while ($row = pg_fetch_assoc($courseResult)) {
              echo "<tr>";
              echo "<td>".htmlspecialchars($row['id'])."</td>";
              echo "<td><img src='".htmlspecialchars($row['thumbnail'])."' alt='Thumbnail'></td>";
              echo "<td>".htmlspecialchars($row['title'])."</td>";
              echo "<td>".htmlspecialchars($row['description'])."</td>";
              echo "<td>".htmlspecialchars($row['category'])."</td>";
              echo "<td>".htmlspecialchars($row['author'])."</td>";
              echo "<td>".htmlspecialchars($row['created_at'])."</td>";
              echo "<td>₹".htmlspecialchars($row['price'])."</td>";
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
function searchCourses() {
  let input = document.getElementById("searchInput").value.toLowerCase();
  let table = document.getElementById("courseTable");
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
