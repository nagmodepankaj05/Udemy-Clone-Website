<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// DB connection
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Get course id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid course ID.");
}
$courseId = (int)$_GET['id'];

// Fetch course details
$query = "SELECT id, title, description, category, author, thumbnail, price, video
          FROM courses WHERE id = $1";
$result = pg_query_params($conn, $query, array($courseId));

if ($result && pg_num_rows($result) === 1) {
    $course = pg_fetch_assoc($result);
} else {
    die("Course not found.");
}
pg_close($conn);

// ✅ Step 1: If Buy Now clicked → generate receipt + force download
if (isset($_GET['buy']) && $_GET['buy'] == "true") {
    $receipt = "***** COURSE PURCHASE RECEIPT *****\n\n";
    $receipt .= "Course Title: " . $course['title'] . "\n";
    $receipt .= "Instructor: " . $course['author'] . "\n";
    $receipt .= "Category: " . $course['category'] . "\n";
    $receipt .= "Price: ₹" . $course['price'] . "\n";
    $receipt .= "Purchased by User ID: " . $_SESSION['user_id'] . "\n";
    $receipt .= "Date: " . date("Y-m-d H:i:s") . "\n";
    $receipt .= "*************************************\n";

    // Force download of receipt
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="receipt_course_' . $courseId . '.txt"');
    echo $receipt;

    // ✅ Step 2: Mark course purchased in session AFTER receipt download
    $_SESSION['my_learning'][$course['id']] = $course;

    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Details</title>
    <link rel="stylesheet" href="course_details.css">
    <link rel="stylesheet" href="coursePages.css">
    <style>
        .buy-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #a435f0;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .buy-btn:hover {
            background: #7a22b5;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="left">
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($course['category']); ?></p>
            <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['author']); ?></p>
            <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($course['price']); ?></p>
            <hr>
            <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            <br>
            <div class="course-info">
                <div>
                    <p>Premium</p>
                    <small>Access this top-rated course + 26,000 more</small>
                </div>
                <div>
                    <p class="rating">4.6 ★</p>
                    <small>547,835 ratings</small>
                </div>
                <div>
                    <p class="learners">2,093,879</p>
                    <small>learners</small>
                </div>
            </div>
        </div>

        <div class="right">
            <div class="course-thumbnail">
                <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>"
                    alt="<?php echo htmlspecialchars($course['title']); ?>"
                    style="max-width:400px;">
            </div>

            <div class="tabs">
                <div class="active">Personal</div>
                <div>Teams</div>
            </div>

            <div class="premium" style="background-color: #fff;">
                This Premium course is included in plans <br>
                Subscribe to Udemy’s top courses
            </div>
            <div class="price">
                <h1>₹ <?php echo htmlspecialchars($course['price']); ?></h1>
            </div>
            <!-- Buy Now Button -->
            <?php if (!isset($_SESSION['my_learning'][$course['id']])): ?>
                <a href="course-details.php?id=<?php echo $course['id']; ?>&buy=true" class="buy-btn">
                    Buy Now
                </a>
            <?php else: ?>
                <p style="color:green; font-weight:bold;">✅ You purchased this course!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Show video if purchased -->
    <?php if (isset($_SESSION['my_learning'][$course['id']])): ?>
        <div class="course-video">
            <h2>Course Video</h2>
            <video width="640" height="360" controls>
                <source src="<?php echo htmlspecialchars($course['video']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    <?php endif; ?>

</body>

</html>