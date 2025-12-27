<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Database connection
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user = "postgres";
$password = "@Pankaj0123456";
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// ✅ Add category
if (isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $check = pg_query_params($conn, "SELECT * FROM categories WHERE category_name=$1", [$category_name]);
        if (pg_num_rows($check) == 0) {
            pg_query_params($conn, "INSERT INTO categories (category_name) VALUES ($1)", [$category_name]);
            echo "<script>alert('✅ Category Added Successfully!'); window.location.href='manage_category.php';</script>";
        } else {
            echo "<script>alert('⚠️ Category already exists!');</script>";
        }
    }
}

// ✅ Delete category
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    pg_query_params($conn, "DELETE FROM categories WHERE id=$1", [$id]);
    echo "<script>alert('🗑️ Category Deleted!'); window.location.href='manage_category.php';</script>";
}

// ✅ Fetch all categories
$result = pg_query($conn, "SELECT * FROM categories ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Categories</title>
<link rel="stylesheet" href="admin.css">
<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f9fc;
  margin: 0;
  padding: 0;
}

/* Navbar */
.navbar {
  background: #17a2b8;
  color: white;
  padding: 15px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.navbar h1 {
  font-size: 22px;
  margin: 0;
}
.navbar a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  margin-left: 15px;
}

/* Container */
.container {
  max-width: 900px;
  background: white;
  margin: 40px auto;
  padding: 25px 35px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.container h2 {
  color: #17a2b8;
  text-align: center;
  margin-bottom: 25px;
}

/* Add Category */
form.add-form {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
  gap: 10px;
}
form.add-form input[type=text] {
  width: 60%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
}
form.add-form button {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}
form.add-form button:hover {
  background: #138496;
}

/* Search Bar (same as course_report) */
.search-bar {
  margin: 20px;
  text-align: center;
}
.search-bar input {
  width: 50%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: center;
}
th {
  background: #17a2b8;
  color: white;
}
td button {
  background: red;
  color: white;
  border: none;
  padding: 6px 14px;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}
td button:hover {
  background: darkred;
}

/* Back button */
.back-btn {
  display: inline-block;
  background: #17a2b8;
  color: white;
  padding: 10px 18px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  margin-bottom: 20px;
  transition: 0.3s;
}
.back-btn:hover {
  background: #138496;
}
</style>
</head>
<body>

<div class="navbar">
  <h1>Manage Categories</h1>
  <div>
    <a href="course_report.php">← Back to Dashboard</a>
  </div>
</div>

<div class="container">
  <h2>🗂️ Add & Manage Course Categories</h2>

  <!-- ✅ Add Category Form -->
  <form method="POST" class="add-form">
    <input type="text" name="category_name" placeholder="Enter new category name..." required>
    <button type="submit" name="add_category">Add Category</button>
  </form>

  <!-- ✅ Search Bar (same as course_report) -->
  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search categories...">
  </div>

  <!-- ✅ Category Table -->
  <table id="categoryTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if ($result && pg_num_rows($result) > 0) {
      while ($row = pg_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['category_name'])."</td>
                <td>
                  <form method='POST' style='display:inline;'>
                    <input type='hidden' name='delete_id' value='{$row['id']}'>
                    <button type='submit'>Delete</button>
                  </form>
                </td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='3' style='color:#555;'>No categories found.</td></tr>";
    }
    ?>
    </tbody>
  </table>
</div>

<script>
// ✅ JavaScript Live Search (same as course_report.php)
document.getElementById("searchInput").addEventListener("keyup", function() {
  let input = this.value.toLowerCase();
  let table = document.getElementById("categoryTable");
  let tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    let tdArray = tr[i].getElementsByTagName("td");
    let found = false;
    for (let j = 0; j < tdArray.length - 1; j++) { // ignore delete column
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
});
</script>

<?php pg_close($conn); ?>
</body>
</html>
