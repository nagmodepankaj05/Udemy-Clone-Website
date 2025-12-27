<?php
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$id = $_GET['id'];
$query = "SELECT * FROM courses WHERE id=$1";
$result = pg_query_params($conn, $query, [$id]);
$course = pg_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $thumbPath = $course['thumbnail'];
    $videoPath = $course['video'];

    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbName = $_FILES['thumbnail']['name'];
        $thumbPath = "uploads/" . basename($thumbName);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbPath);
    }

    if (!empty($_FILES['video']['name'])) {
        $videoName = $_FILES['video']['name'];
        $videoPath = "uploads/" . basename($videoName);
        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);
    }

    $updateQuery = "UPDATE courses 
                    SET title=$1, description=$2, category=$3, thumbnail=$4, video=$5 , price=$6
                    WHERE id=$7";
    $updateResult = pg_query_params($conn, $updateQuery, [$title, $description, $category, $thumbPath, $videoPath, $price, $id]);

    if ($updateResult) {
        echo "<script>alert('Course Updated Successfully!'); window.location.href='courses.php';</script>";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link rel="stylesheet" href="EditCourse.css">
</head>
<body>
    <h2>Edit Course</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Course Title:</label><br>
        <input type="text" name="title" value="<?php echo $course['title']; ?>" required><br>

        <label>Course Description:</label><br>
        <textarea name="description" required><?php echo $course['description']; ?></textarea><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="Programming" <?php if($course['category']=='Programming') echo 'selected'; ?>>Programming</option>
            <option value="Design" <?php if($course['category']=='Design') echo 'selected'; ?>>Design</option>
            <option value="Marketing" <?php if($course['category']=='Marketing') echo 'selected'; ?>>Marketing</option>
            <option value="Business" <?php if($course['category']=='Business') echo 'selected'; ?>>Business</option>
        </select><br>

        <label>Update Thumbnail (optional):</label><br>
        <input type="file" name="thumbnail" accept="image/*"><br>

        <label>Update Video (optional):</label><br>
        <input type="file" name="video" accept="video/*"><br>

        <label>Update Course Price (₹):</label>
        <input type="number" name="price" step="0.01" required><br>


        <button type="submit">Update Course</button>
    </form>
</body>
</html>
