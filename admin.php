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

// Count total users
$userCountResult = pg_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$userCount = pg_fetch_result($userCountResult, 0, 'total_users');

// Count total instructors
$instructorCountResult = pg_query($conn, "SELECT COUNT(*) AS total_instructors FROM instructor");
$instructorCount = pg_fetch_result($instructorCountResult, 0, 'total_instructors');

// Count total courses
$courseCountResult = pg_query($conn, "SELECT COUNT(*) AS total_courses FROM courses");
$courseCount = pg_fetch_result($courseCountResult, 0, 'total_courses');

// Logged-in admin ID from session
$adminId = $_SESSION['admin_id'];

// ✅ Correct table and column names
$query = "SELECT full_name, email, role, created_at FROM admin WHERE admin_id = $1";
$result = pg_query_params($conn, $query, array($adminId));

if (!$result) {
    die("Query failed: " . pg_last_error());
}

$admin = pg_fetch_assoc($result);

if (!$admin) {
    die("Admin not found.");
}

// Extract data for display
$adminName   = $admin['full_name'];
$adminEmail  = $admin['email'];
$adminRole   = $admin['role'];
$adminJoined = $admin['created_at'];
$firstLetter = strtoupper(substr($adminName, 0, 1));

pg_free_result($result);
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search != "") {
    // Search query
    $userQuery = "SELECT id, fullname, dob, username, password FROM users ORDER BY id ASC";

    $userResult = pg_query_params($conn, $userQuery, array("%$search%"));
} else {
    // Fetch all users
    $userQuery = "SELECT id, fullname, dob, username, password FROM users ORDER BY id ASC";

    $userResult = pg_query($conn, $userQuery);
}

if (!$userResult) {
    die("User query failed: " . pg_last_error());
}

$users = pg_fetch_all($userResult);

pg_free_result($userResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
            <h1>Admin Dashboard</h1>
            <div class="navbar-menu">
                <!-- <a href="#">Users</a>
                <a href="#">Instructors</a>
                <a href="#">Courses</a> -->
            </div>
        </div>

        <div class="profile">
            <div class="profile-icon"><?= $firstLetter ?></div>
            <div class="dropdown">
                <strong>Welcome <?= htmlspecialchars($adminName) ?></strong>
                <p class="dob">Role: <?= htmlspecialchars($adminRole) ?></p>
                <p><?= htmlspecialchars($adminEmail) ?></p>
                <p>Joined: <?= date('Y-m-d', strtotime($adminJoined)) ?></p>
                <p><a href="logout.php">Log Out</a></p>
            </div>
        </div>

    </div>

    <!-- Main Content -->
    <!-- Summary Boxes -->
    <div class="summary-boxes">
        <div class="summary-box">
            <h3>Total Users</h3>
            <p><?= $userCount ?></p>
        </div>
        <div class="summary-box">
            <h3>Total Instructors</h3>
            <p><?= $instructorCount ?></p>
        </div>
        <div class="summary-box">
            <h3>Total Courses</h3>
            <p><?= $courseCount ?></p>
        </div>
    </div>

    <div class="dashboard-boxes">
        <a href="user_report.php" class="box">User Report</a>
        <a href="instructor_report.php" class="box">Instructor Report</a>
        <a href="course_report.php" class="box">Courses Report</a>
    </div>
    </div>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search by name or email..." onkeyup="searchUsers()">
    </div>

    <!-- User Table -->
    <h2>User List</h2>
    <table id="userTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>DOB</th>
                <th>Username</th>
                <th>Delete Users</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch ALL users from DB once
            $userQuery = "SELECT id, fullname, dob, username FROM users ORDER BY id ASC";
            $userResult = pg_query($conn, $userQuery);
            if ($userResult) {
                while ($row = pg_fetch_assoc($userResult)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
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
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#userTable tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
    <?php pg_close($conn); ?>
</body>

</html>