<?php
session_start(); // Required for login session

$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// ✅ Fetch logged-in instructor info
$userData = null;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT name, dob, email, username FROM instructor WHERE id = $1";
    $result2 = pg_query_params($conn, $query, array($_SESSION['user_id']));

    if ($result2 && pg_num_rows($result2) === 1) {
        $userData = pg_fetch_assoc($result2);
    }
}

// ✅ Fetch courses uploaded by this instructor using instructor name
$courses = [];
if ($userData) {
    $queryCourses = "SELECT * FROM courses WHERE author = $1 ORDER BY created_at DESC";
    $resultCourses = pg_query_params($conn, $queryCourses, array($userData['name']));
    if ($resultCourses) {
        while ($row = pg_fetch_assoc($resultCourses)) {
            $courses[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="instructorLogin.css">
    <link rel="stylesheet" href="cources.css">
    <link rel="icon" href="favicon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav>
        <div class="nav-section">
            <div class="navbar">
                <div class="nav-item-logo">
                    <img src="images/logo-udemy-inverted2.svg">
                </div>
                <div class="nav-item">
                    <a href="instructorLog.php">
                        <i class="fa-solid fa-upload" style="color: #f7f3f3ff;"></i>
                        <span style="padding-left:20px">Upload</span>
                    </a>
                </div>
                <div class="nav-item" style="background-color: rgba(111, 107, 115, 1); border-left: 5px solid #6d28d2;">
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
                    <h2>Instructor Courses</h2>
                    <a href="create_cource.php">+ Create New Course</a>
                    <div style="margin-top:20px;" class="cource_data">
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $row): ?>
                                <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                                    <div class="cource_info">
                                        <div class="thumbnail">
                                            <?php if (!empty($row['thumbnail'])): ?>
                                                <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" width="200"><br><br>
                                            <?php endif; ?>
                                        </div>
                                        <div class="desc">
                                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                            <p><b>Category:</b> <?php echo htmlspecialchars($row['category']); ?></p>
                                            <p><b>Author:</b> <?php echo htmlspecialchars($row['author']); ?></p>
                                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                                            <p><b>Price:</b> <?php echo htmlspecialchars($row['price']); ?></p>
                                        </div>
                                        <div class="cource_video">
                                            <?php if (!empty($row['video'])): ?>
                                                <video width="320" controls>
                                                    <source src="<?php echo htmlspecialchars($row['video']); ?>" type="video/mp4">
                                                </video>
                                                <br><br>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="course_edit">
                                        <!-- Edit & Delete -->
                                        <a href="edit_course.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> |
                                        <a href="delete_course.php?id=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this course?');">❌ Delete</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No courses uploaded yet.</p>
                        <?php endif; ?>
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
                        <button class="userIcon"
                            style="background:#A435F0; color:#fff; border-radius:50%; width:35px; height:35px; font-weight:bold; border:none;">
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