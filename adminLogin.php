<?php
session_start();

// DB connection
$host = "localhost";
$port = "5432";
$dbname = "udemy";
$user  = "postgres";
$password = "@Pankaj0123456";
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $passwordInput = trim($_POST['password']);

    $query = "SELECT * FROM admin WHERE username = $1 LIMIT 1";
    $result = pg_query_params($conn, $query, array($username));

    if ($result && pg_num_rows($result) > 0) {
        $admin = pg_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($passwordInput, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            echo "<script>
                    alert('Login successfully ✅');
                    window.location.href='admin.php';
                  </script>";
            exit();
        } else {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        }
    } else {
        echo "<p style='color:red;'>Admin not found.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" href="favicon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav>
        <div class="nevbar">
            <div class="nevbar-logo">
                <img src="images/logo-udemy.svg" alt="Logo">
            </div>
            <div class="nevbar-Explore">
                <div class="explore-text">Explore</div>
            </div>
            <div class="nevbar-searchbar">
                <input type="text" placeholder="Search for anything...">
            </div>
            <div class="nevbar-elements">
                <li><div class="elements-text">Plans & pricing</div></li>
                <li><div class="elements-text">Udemy Business</div></li>
                <li><div class="elements-text">Teach on Udemy</div></li>
                <li><div class="elements-text"><i class="fa-solid fa-cart-shopping"></i></div></li>
                <li>
                    <button><a href="login.php">Log in</a></button>
                </li>
                <li class="SignUp">
                    <button><a href="signUp.html">Sign up</a></button>
                </li>
            </div>
        </div>
    </nav>
    <main>
        <div class="signUp-main">
            <div class="divider1">
                <img src="images/login-img1.webp" alt="Login Image">
            </div>
            <div class="divider2">
                <h1>Admin Login</h1>
                <form method="POST" id="myForm">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Continue to Login</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
