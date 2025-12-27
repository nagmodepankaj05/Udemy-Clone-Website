<?php
session_start(); // ✅ Important for login session

$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// ✅ Fetch logged-in instructor info (if session set)
$userData = null;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT name, dob, email, username FROM instructor WHERE id = $1";
    $result2 = pg_query_params($conn, $query, array($_SESSION['user_id']));

    if ($result2 && pg_num_rows($result2) === 1) {
        $userData = pg_fetch_assoc($result2);
    }
}

// ✅ Handle course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    // File uploads
    $thumbName = $_FILES['thumbnail']['name'];
    $videoName = $_FILES['video']['name'];

    $thumbPath = "uploads/" . basename($thumbName);
    $videoPath = "uploads/" . basename($videoName);

    move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbPath);
    move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);

    $query = "INSERT INTO courses (title, description, category, author, thumbnail, video, price) 
          VALUES ($1, $2, $3, $4, $5, $6, $7)";
    $result = pg_query_params($conn, $query, [$title, $description, $category, $author, $thumbPath, $videoPath, $price]);

    if ($result) {
        echo "<script>alert('Course Created Successfully!'); window.location.href='courses.php';</script>";
        exit();
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="instructorLogin.css">
    <link rel="stylesheet" href="createCourse.css">
    <link rel="icon" href="favicon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav>
        <div class="nav-section">
            <div class="navbar">
                <div class="nav-item-logo">
                    <img src="images/logo-udemy-inverted2.svg">
                </div>
                <div class="nav-item" style="background-color: rgba(111, 107, 115, 1); border-left: 5px solid #6d28d2; ">
                    <a href="create_course.php">
                        <i class="fa-solid fa-upload" style="color: #f7f3f3ff;"></i>
                        <span style="padding-left:20px">Upload</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="courses.php">
                        <i class="fa-solid fa-tv" style="color: #ffffff;"></i>
                        <span style="padding-left:20px">Courses</span>
                    </a>
                </div>
                <div class="nav-item" style="padding-left: 5px;">
                    <a href="logout.php">
                        <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                        <span style="padding-left:20px">Logout</span>
                    </a>
                </div>
            </div>
            <aside>
                <div class="content">
                    <div class="content-title">Upload</div>
                    <hr>
                    <div class="cource-container">
                        <div class="course-data">
                            <h2>Create a New Course</h2>
                            <form method="POST" enctype="multipart/form-data">
                                <label>Course Title:</label>
                                <input type="text" name="title" required>

                                <label>Course Description:</label>
                                <textarea name="description" required></textarea>

                                <label>Category:</label>
                                <select name="category" required>
                                    <?php
                                    $catResult = pg_query($conn, "SELECT category_name FROM categories ORDER BY category_name ASC");
                                    if ($catResult && pg_num_rows($catResult) > 0) {
                                        while ($cat = pg_fetch_assoc($catResult)) {
                                            echo "<option value='" . htmlspecialchars($cat['category_name']) . "'>" . htmlspecialchars($cat['category_name']) . "</option>";
                                        }
                                    } else {
                                        echo "<option disabled>No categories available</option>";
                                    }
                                    ?>
                                </select><br>

                                <label>Author Name:</label>
                                <input type="text" name="author" value="<?php echo $userData ? htmlspecialchars($userData['name']) : ''; ?>" required>

                                <label>Upload Thumbnail:</label>
                                <input type="file" name="thumbnail" accept="image/*" required>

                                <label>Upload Course Video:</label>
                                <input type="file" name="video" accept="video/*" required>

                                <label>Course Price (₹):</label>
                                <input type="number" name="price" step="0.01" required>


                                <button type="submit">Publish Course</button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="side-nav">
                <div class="side-nav-item">
                    <a href="login.html">Student</a>
                </div>
                <div class="side-nav-item">
                    <?php if ($userData): ?>
                        <!-- Show first letter of instructor name -->
                        <button class="userIcon" style="background:#A435F0; color:#fff; border-radius:50%; width:35px; height:35px; font-weight:bold; border:none;">
                            <?php echo strtoupper(substr($userData['name'], 0, 1)); ?>
                        </button>
                        <div class="user-info" id="userInfoSec">
                            <h2>Welcome <?php echo htmlspecialchars($userData['name']); ?></h2>
                            <p><strong><?php echo htmlspecialchars($userData['email']); ?></strong></p>
                            <p><strong>DOB : <?php echo htmlspecialchars($userData['dob']); ?></strong></p>
                            <hr>
                            <div class="userInfoSec">
                                <div class="cartInfo"><a href="logout.php">Log Out</a></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="userIcon"><i class="fa-solid fa-circle-user" style="color: #A435F0;"></i></button>
                        <div class="user-info" id="userInfoSec">
                            <p><a href="login.html">Login</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <script src="script.js"></script>
</body>

</html>