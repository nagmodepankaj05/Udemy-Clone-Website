<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: instructorLogin.html");
    exit();
}

$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$query = "SELECT name, dob,email, username FROM instructor WHERE id = $1";
$result = pg_query_params($conn, $query, array($_SESSION['user_id']));

if ($result && pg_num_rows($result) === 1) {
    $userData = pg_fetch_assoc($result);
} else {
    echo "Error fetching user details.";
    exit();
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Web Devlopment & Programming courses</title>
    <link rel="stylesheet" href="instructorLogin.css">
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
                    <a href="instructorLog.php">
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
                    <a href="#">
                        <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                        <span style="padding-left:20px">Logout</span>
                    </a>
                </div>
                <!-- <div class="nav-item">
                
                </div> -->
            </div>
            <aside>
                <div class="content">
                    <div class="content-title">Upload</div>
                    <hr>
                    <div class="welcome-text">
                        Welcome, <?php echo htmlspecialchars($userData['name']); ?> <br>
                        <span class="right">Create your course now</span>
                    </div>
                    <div class="create-box">
                        <div class="create-text">
                            Jump Into Course Creation
                        </div>
                        <div class="create-cource-btn">
                            <button><a href="create_cource.php">Create Your Cource</a></button>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="side-nav">
                <div class="side-nav-item">
                    <a href="#">Student</a>
                </div>
                <div class="side-nav-item">
                    <?php if (isset($userData) && !empty($userData['name'])): ?>
                        <!-- Show first letter of username -->
                        <button class="userIcon" style="background:#A435F0; color:#fff; border-radius:50%; width:35px; height:35px; font-weight:bold; border:none;">
                            <?php echo strtoupper(substr($userData['name'], 0, 1)); ?>
                        </button>
                    <?php else: ?>
                        <!-- Default user icon if not logged in -->
                        <button class="userIcon">
                            <i class="fa-solid fa-circle-user" style="color: #A435F0;"></i>
                        </button>
                    <?php endif; ?>

                    <div class="user-info" id="userInfoSec">
                        <?php if (isset($userData)): ?>
                            <h2>Welcome <?php echo htmlspecialchars($userData['name']); ?></h2>
                            <p><strong><?php echo htmlspecialchars($userData['email']); ?></strong></p>
                            <p><strong>DOB : <?php echo htmlspecialchars($userData['dob']); ?></strong></p>
                            <hr>
                            <div class="userInfoSec">
                                <div class="cartInfo"><a href="logout.php">Log Out</a></div>
                            </div>
                        <?php else: ?>
                            <p><a href="login.php">Login</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </nav>
    <script src="script.js"></script>
</body>

</html>