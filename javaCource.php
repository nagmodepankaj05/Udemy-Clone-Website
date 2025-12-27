<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
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

$query = "SELECT fullname, dob, username FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($_SESSION['user_id']));

if ($result && pg_num_rows($result) === 1) {
    $userData = pg_fetch_assoc($result);
} else {
    echo "Error fetching user details.";
    exit();
}

$coursesQuery = "SELECT id, title, description, category, author, thumbnail, price FROM courses ORDER BY id DESC";
$coursesResult = pg_query($conn, $coursesQuery);

if (!$coursesResult) {
    die("Error fetching courses: " . pg_last_error($conn));
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Web Devlopment & Programming courses</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="userLogin.css">
    <link rel="stylesheet" href="coursePages.css">
    <link rel="icon" href="favicon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="navbar">
        <div class="nevbar-logo">
            <img src="images/logo-udemy.svg">
        </div>
        <div class="explore">Explore</div>

        <div class="search-bar">
            <input type="text" id="searchBox" placeholder="Search for anything">
            <div class="dropdown" id="dropdown"></div>
        </div>
        <div class="nevbar-elements">
            <li>
                <div class="elements-text" style="color: black;">Plans & pricing</div>
            </li>
            <li>
                <div class="elements-text"><a href="instructorLogin.html">Teach on Udemy</a></div>
            </li>
            <!-- <li>
                <div class="elements-text"><i class="fa-solid fa-cart-shopping"></i></div>
            </li> -->
            <li>
                <?php if (isset($userData) && !empty($userData['fullname'])): ?>
                    <!-- Show first letter of username -->
                    <button class="userIcon" style="background:#A435F0; color:#fff; border-radius:50%; width:35px; height:35px; font-weight:bold; border:none;">
                        <?php echo strtoupper(substr($userData['fullname'], 0, 1)); ?>
                    </button>
                <?php else: ?>
                    <!-- Default user icon if not logged in -->
                    <button class="userIcon">
                        <i class="fa-solid fa-circle-user" style="color: #A435F0;"></i>
                    </button>
                <?php endif; ?>

                <div class="user-info" id="userInfoSec">
                    <?php if (isset($userData)): ?>
                        <h2>Welcome <?php echo htmlspecialchars($userData['fullname']); ?></h2>
                        <p><strong>DOB : <?php echo htmlspecialchars($userData['dob']); ?></strong></p>
                        <hr>
                        <div class="userInfoSec">
                            <div class="cartInfo"><a href="#" id="myCartLink">My Cart (<span id="cart-count">0</span>)</a></div>
                            <div id="cart-items" style="max-height:200px; overflow-y:auto; border:1px solid #ddd; padding:5px; margin-top:5px;">
                                <p>No items in cart</p>
                            </div>
                            <div class="cartInfo"><a href="#">My Learning</a></div>
                            <div class="cartInfo"><a href="#">Purchase History</a></div>
                            <div class="cartInfo"><a href="logout.php">Log Out</a></div>
                        </div>
                    <?php else: ?>
                        <p><a href="login.php">Login</a></p>
                    <?php endif; ?>
                </div>
            </li>

        </div>
    </div>

    <div class="subnav">
        <a href="#" class="active">Development</a>
        <a href="#">Web Development</a>
        <a href="#">Data Science</a>
        <a href="#">Mobile Development</a>
        <a href="#">Programming Languages</a>
        <a href="#">Game Development</a>
        <a href="#">Database Design & Development</a>
        <a href="#" class="mag-left">⋮</a>
    </div>

    <div class="container">

        <div class="left">
            <h3>Development > Programming Languages > Python</h3>
            <h1>The Complete Python Bootcamp From Zero to Hero in Python</h1>
            <p>Learn Python like a Professional Start from the basics and go all the way to creating your own applications and games</p>

            <div class="creator">
                Created by <a href="#">Jose Portilla</a>, <a href="#">Pierian Training</a>
            </div>

            <div class="meta">
                Last updated 8/2025 • English [Auto], Arabic [Auto], 19 more
            </div>

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
            <img src="images/javatutor.jpeg" alt="Course Preview">

            <div class="tabs">
                <div class="active">Personal</div>
                <div>Teams</div>
            </div>

            <div class="premium" style="background-color: #fff;">
                This Premium course is included in plans <br>
                Subscribe to Udemy’s top courses
            </div>
            <div class="price">
                <h1>₹499.00</h1>
            </div>
            <a href="buy.php">
                <button>Buy Now</button>
            </a>
        </div>
    </div>
    <div class="all-controls">
        <div class="all-course-container" id="allCourseContainer">
            <?php while ($course = pg_fetch_assoc($coursesResult)): ?>
                <div class="all-course-card" data-category="<?= htmlspecialchars($course['category']) ?>">
                    <img src="<?= htmlspecialchars($course['thumbnail']) ?>" alt="Course Image" class="all-course-img">
                    <div class="all-course-info">
                        <div class="all-course-title"><?= htmlspecialchars($course['title']) ?></div>
                        <div class="all-course-desc"><?= htmlspecialchars($course['description']) ?></div>
                        <div class="all-author">By <?= htmlspecialchars($course['author']) ?></div>
                        <div class="all-tags"><span class="premium">Premium</span></div>
                    </div>
                    <div class="details-sec">
                        <div class="all-price">₹<?= htmlspecialchars($course['price']) ?></div>
                        <div class="addCart">
                            <button class="addCartbtn"
                                onclick="addToCart('<?= htmlspecialchars($course['title']) ?>', <?= $course['price'] ?>, '<?= htmlspecialchars($course['thumbnail']) ?>', 'course-details.php?id=<?= $course['id'] ?>')">
                                Add To Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
    <footer>
        <div class="footer-top">
            <div class="footer-top-text">
                Top companies choose <a href="#">Udemy Business</a> to build in-demand career skills.
            </div>
            <div class="footer-logos">
                <img src="images/nasdaq-light.svg" alt="Nasdaq">
                <img src="images/Volkswagen_logo_2019.svg" alt="Volkswagen">
                <img src="images/netapp-light.svg" alt="NetApp">
                <img src="images/eventbrite-light.svg" alt="Eventbrite">
            </div>
        </div>

        <div class="footer-container">
            <div>
                <h3>In-demand Careers</h3>
                <ul>
                    <li><a href="#">Data Scientist</a></li>
                    <li><a href="#">Full Stack Web Developer</a></li>
                    <li><a href="#">Cloud Engineer</a></li>
                    <li><a href="#">Project Manager</a></li>
                    <li><a href="#">Game Developer</a></li>
                    <li><a href="#">All Career Accelerators</a></li>
                </ul>
            </div>

            <div>
                <h3>Web Development</h3>
                <ul>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">JavaScript</a></li>
                    <li><a href="#">React JS</a></li>
                    <li><a href="#">Angular</a></li>
                    <li><a href="#">Java</a></li>
                </ul>
            </div>

            <div>
                <h3>IT Certifications</h3>
                <ul>
                    <li><a href="#">Amazon AWS</a></li>
                    <li><a href="#">AWS Certified Cloud Practitioner</a></li>
                    <li><a href="#">AZ-900: Microsoft Azure Fundamentals</a></li>
                    <li><a href="#">AWS Solutions Architect</a></li>
                    <li><a href="#">Kubernetes</a></li>
                </ul>
            </div>

            <div>
                <h3>Leadership</h3>
                <ul>
                    <li><a href="#">Leadership</a></li>
                    <li><a href="#">Management Skills</a></li>
                    <li><a href="#">Project Management</a></li>
                    <li><a href="#">Personal Productivity</a></li>
                    <li><a href="#">Emotional Intelligence</a></li>
                </ul>
            </div>

            <div>
                <h3>Certifications by Skill</h3>
                <ul>
                    <li><a href="#">Cybersecurity Certification</a></li>
                    <li><a href="#">Project Management Certification</a></li>
                    <li><a href="#">Cloud Certification</a></li>
                    <li><a href="#">Data Analytics Certification</a></li>
                    <li><a href="#">HR Management Certification</a></li>
                    <li><a href="#">See all Certifications</a></li>
                </ul>
            </div>

            <div>
                <h3>Data Science</h3>
                <ul>
                    <li><a href="#">Data Science</a></li>
                    <li><a href="#">Python</a></li>
                    <li><a href="#">Machine Learning</a></li>
                    <li><a href="#">ChatGPT</a></li>
                    <li><a href="#">Deep Learning</a></li>
                </ul>
            </div>

            <div>
                <h3>Communication</h3>
                <ul>
                    <li><a href="#">Communication Skills</a></li>
                    <li><a href="#">Presentation Skills</a></li>
                    <li><a href="#">Public Speaking</a></li>
                    <li><a href="#">Writing</a></li>
                    <li><a href="#">PowerPoint</a></li>
                </ul>
            </div>

            <div>
                <h3>Business Analytics & Intelligence</h3>
                <ul>
                    <li><a href="#">Microsoft Excel</a></li>
                    <li><a href="#">SQL</a></li>
                    <li><a href="#">Microsoft Power BI</a></li>
                    <li><a href="#">Data Analysis</a></li>
                    <li><a href="#">Business Analysis</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <footer class="last-footer">
        <div class="last-footer-top">
            <div class="last-footer-column">
                <h4>About</h4>
                <ul>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Investors</a></li>
                </ul>
            </div>

            <div class="last-footer-column">
                <h4>Discover Udemy</h4>
                <ul>
                    <li><a href="#">Get the app</a></li>
                    <li><a href="#">Teach on Udemy</a></li>
                    <li><a href="#">Plans and Pricing</a></li>
                    <li><a href="#">Affiliate</a></li>
                    <li><a href="#">Help and Support</a></li>
                </ul>
            </div>

            <div class="last-footer-column">
                <h4>Udemy for Business</h4>
                <ul>
                    <li><a href="#">Udemy Business</a></li>
                </ul>
            </div>

            <div class="last-footer-column">
                <h4>Legal & Accessibility</h4>
                <ul>
                    <li><a href="#">Accessibility statement</a></li>
                    <li><a href="#">Privacy policy</a></li>
                    <li><a href="#">Sitemap</a></li>
                    <li><a href="#">Terms</a></li>
                </ul>
            </div>
        </div>

        <div class="last-footer-bottom">
            <div class="last-footer-logo">
                <img src="images/logo-udemy-inverted.svg" alt="Udemy Logo">
                <p>© 2025 Udemy, Inc.</p>
            </div>

            <div class="last-footer-settings">
                <p>Cookie settings</p>
                <a href="#">🌐 English</a>
            </div>
        </div>
    </footer>
    <script src="script.js"></script>
    <script src="userLoginScript.js"></script>
</body>

</html>