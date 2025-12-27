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

        <div class="search-container">
            <div class="search-bar">
                <input type="text" id="searchBox" placeholder="Search for anything">
            </div>

            <div id="dropdown" class="search-dropdown"></div>
        </div>

        <div class="nevbar-elements">
            <li>
                <div class="elements-text">Plans & pricing</div>
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
        <div class="dev-tex-sec">
            <div class="section-name">
                <h1>Development Courses</h1>
            </div>
            <h2>Courses to get you started</h2>
            <p>Explore courses from experienced, real-world experts.</p>
        </div>
        <div class="tabs">
            <div class="tab active">Most popular</div>
        </div>

        <div class="courses">
            <div class="course-card">
                <img src="images/javatutor.jpeg" alt="Java Tutorial">
                <div class="course-content">
                    <div class="course-title">Java Tutorial for Complete Beginners</div>
                    <div class="course-author">John Purcell</div>
                    <div class="rating">★ 4.4 (102,435)</div>
                    <div class="price">₹499 <span class="old-price">₹799</span></div>
                    <div class="tags">
                        <span class="tag">Premium</span>
                    </div>
                    <div class="addCart">
                        <button class="addCartbtn" onclick="addToCart('Java Tutorial for Complete Beginners', 499, 'images/javatutor.jpeg', 'javaCource.php')">Add To Cart</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <img src="images/pythonbootcamp.jpeg" alt="Python Bootcamp">
                <div class="course-content">
                    <div class="course-title">The Complete Python Bootcamp From Zero to Hero in Python</div>
                    <div class="course-author">Jose Portilla, Pierian Training</div>
                    <div class="rating">★ 4.6 (544,066)</div>
                    <div class="price">₹479 <span class="old-price">₹3,109</span></div>
                    <div class="tags">
                        <span class="tag">Premium</span>
                    </div>
                    <div class="addCart">
                        <button class="addCartbtn" onclick="addToCart('The Complete Python Bootcamp', 479, 'images/pythonbootcamp.jpeg', 'pythoncourse.html')">Add To Cart</button>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <img src="images/100daysofcode.jpeg" alt="100 Days of Code">
                <div class="course-content">
                    <div class="course-title">100 Days of Code: The Complete Python Pro Bootcamp</div>
                    <div class="course-author">Dr. Angela Yu</div>
                    <div class="rating">★ 4.7 (385,951)</div>
                    <div class="price">₹499 <span class="old-price">₹3,219</span></div>
                    <div class="tags">
                        <span class="tag">Premium</span>
                        <span class="tag bestseller">Bestseller</span>
                    </div>
                    <div class="addCart">
                        <button class="addCartbtn" onclick="addToCart('100 Days of Code: The Complete Python Pro Bootcamp', 499, 'images/100daysofcode.jpeg', 'java-details.html')">Add To Cart</button>
                    </div>
                </div>
            </div>



            <div class="course-card">
                <img src="images/completefullstack.jpeg" alt="Full-Stack Web Development">
                <div class="course-content">
                    <div class="course-title">The Complete Full-Stack Web Development Bootcamp</div>
                    <div class="course-author">Dr. Angela Yu</div>
                    <div class="rating">★ 4.7 (448,887)</div>
                    <div class="price">₹479 <span class="old-price">₹3,109</span></div>
                    <div class="tags">
                        <span class="tag">Premium</span>
                        <span class="tag bestseller">Bestseller</span>
                    </div>
                    <div class="addCart">
                        <button class="addCartbtn" onclick="addToCart('100 Days of Code: The Complete Python Pro Bootcamp', 499, 'images/completefullstack.jpeg', 'java-details.html')">Add To Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="top-features">
        <div>Learn in-demand skills with over 250,000 video courses</div>
        <div> Choose courses taught by real-world experts</div>
        <div> Learn at your own pace, with lifetime access on mobile and desktop</div>
    </div>

    <div class="featured-section">
        <h2>Featured courses</h2>
        <p>Many learners enjoyed this highly rated course for its engaging content.</p>

        <div class="top-course-card">
            <img src="images/featuredsection.webp" alt="Course Image">
            <div class="course-info">
                <h3>Unreal Engine 5 C++ Multiplayer CRASH COURSE</h3>
                <p>Quickly learn the fundamentals of Unreal Engine's Multiplayer<br> Framework!</p>
                <p><strong>By Stephen Ulibarri</strong></p>
                <p>Updated <strong>July 2025</strong> • 5 total hours • 29 lectures • All Levels</p>
                <p class="top-rating">4.8 ★★★★☆ (73) <span class="badge">Hot & New</span></p>
                <br>
                <p class="top-price">₹2,069</p>
                <div class="addCart">
                    <button class="addCartbtn" onclick="addToCart('Unreal Engine 5 C++ Multiplayer CRASH COURSE', 2069, 'images/featuredsection.webp', 'java-details.html')">Add To Cart</button>
                </div>
            </div>
        </div>
    </div>


    <div class="popular-topics">
        <h2>Popular topics</h2>
        <div class="topics-container">
            <div class="topic-box"><a href="#">Python</a></div>
            <div class="topic-box"><a href="#">Web Development</a></div>
            <div class="topic-box"><a href="#">Artificial Intelligence (AI)</a></div>
            <div class="topic-box"><a href="#">React JS</a></div>
            <div class="topic-box"><a href="#">JavaScript</a></div>
            <div class="topic-box"><a href="#">AI Agents</a></div>
            <div class="topic-box"><a href="#">Data Science</a></div>
            <div class="topic-box"><a href="#">Unreal Engine</a></div>
            <div class="topic-box"><a href="#">Java</a></div>
            <div class="topic-box"><a href="#">Machine Learning</a></div>
        </div>
    </div>
    <hr>

    <div class="all-header">All Development courses</div>
    <div class="all-sub-header">Not sure? All courses have a 30-day money-back guarantee</div>

    <div class="all-controls">

        <!-- <button class="all-btn" id="allFilterBtn" type="button">Filter</button> -->
        <select id="filterCategory"></select>

        <!-- <div class="all-sort-wrap">
            <button class="all-btn all-sort-btn" id="allSortBtn" type="button">
                Sort by <span class="all-caret"></span>
            </button>
            <div class="all-sort-menu" id="allSortMenu">
                <div class="all-sort-item" data-sort="rating">Highest Rated</div>
                <div class="all-sort-item" data-sort="popular">Most Popular</div>
                <div class="all-sort-item" data-sort="newest">Newest</div>
            </div>
        </div> -->

        <!-- <div class="all-filter-panel" id="allFilterPanel">
            <div class="all-filter-section">
                <div class="all-filter-title">Topic</div>
                <label class="all-filter-option">
                    <input type="checkbox" id="allTopicPython"> Python
                </label>
                <label class="all-filter-option">
                    <input type="checkbox" id="allTopicWebdev"> Web Development
                </label>
                <label class="all-filter-option">
                    <input type="checkbox" id="allTopicDs"> Data Science
                </label>
            </div>
        </div> -->
    </div>

    <div class="all-course-container" id="allCourseContainer">
        <div class="all-course-card" data-category="python" data-rating="4.6" data-popularity="544186"
            data-date="2024-11-10">
            <img src="images/pythonbootcamp.jpeg" alt="Python Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">
                    The Complete Python Bootcamp From Zero to Hero in Python
                </div>
                <div class="all-course-desc">
                    Learn Python like a Professional. Start from basics to creating
                    applications and games.
                </div>
                <div class="all-rating">4.6 ★ (544,186)</div>
                <div class="all-tags">
                    <span class="premium">Premium</span>
                </div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹3,109</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>

        <div class="all-course-card" data-category="wordpress" data-rating="5.0" data-popularity="571186"
            data-date="2024-11-10">
            <img src="images/wordpress.jpeg" alt="word Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">How to create a website with wordpress and Elementor in 2024</div>
                <div class="all-course-desc">A complete guide to building a Stunning and a Professional website with
                    wordpress and
                    Elementor <br>for Absolute Beginners </div>
                <div class="all-rating">5.0 ★ (562,186)</div>
                <div class="all-tags"><span class="premium">Premium</span></div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹799</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>

        <div class="all-course-card" data-category="Gaming" data-rating="4.9" data-popularity="571186"
            data-date="2024-11-10">
            <img src="images/Zombie.jpeg" alt="Game Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">Make a horror Zombie Game in Unity</div>
                <div class="all-course-desc">Make a horror Zombie Game in Unity</div>
                <div class="all-rating">5.0 ★ (56)</div>
                <div class="all-tags"><span class="premium">Premium</span><span class="Gaming">Gaming</span></div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹899</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>

        <div class="all-course-card" data-category="react" data-rating="4.3" data-popularity="581186"
            data-date="2024-11-10">
            <img src="images/react.jpeg" alt="React Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">React - The Complete Giude for 2025 (incl. Next.js, Redux) </div>
                <div class="all-course-desc">Dive in and Learn React.js from Scratch! Learn React, Hooks, Redux, React
                    Router,
                    Next.js Beat<br>practices And way More !</div>
                <div class="all-rating">4.6 ★ (56)</div>
                <div class="all-tags"><span class="premium">Premium</span><span class="bestseller">Bestseller</span>
                </div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹3779</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>

        <div class="all-course-card" data-category="python" data-rating="4.7" data-popularity="591184"
            data-date="2024-11-10">
            <img src="images/pythonmasterclass.jpeg" alt="Python Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">Learn Python Programming Masterclass</div>
                <div class="all-course-desc"> This Python for Beginners Course Teaches You The Python Language fast.
                    Includes
                    Python<br>Online Training With Python 3 </div>
                <div class="all-rating">4.6 ★ (104609)</div>
                <div class="all-tags"><span class="premium">Premium</span><span class="bestseller">Bestseller</span>
                </div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹4469</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>

        <div class="all-course-card" data-category="ds" data-rating="4.7" data-popularity="591184"
            data-date="2024-11-10">
            <img src="images/ds1.jpg" alt="data Science Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">The Data Science Course: Complete Data Science Bootcamp 2025</div>
                <div class="all-course-desc"> Complete Data Science Training: Math, Statistics, Python, Advanced
                    Statistics
                    in Python, Machine and Deep Learning </div>
                <div class="all-rating">4.6 ★ (104609)</div>
                <div class="all-tags"><span class="premium">Premium</span><span class="bestseller">Bestseller</span>
                </div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹4469</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>

        </div>

        <div class="all-course-card" data-category="webdev" data-rating="4.7" data-popularity="448992"
            data-date="2025-02-05">
            <img src="images/webstack.jpeg" alt="Web Dev Course" class="all-course-img">
            <div class="all-course-info">
                <div class="all-course-title">The Complete Full-Stack Web Development Bootcamp</div>
                <div class="all-course-desc">Become a Full-Stack Web Developer. HTML, CSS, JS, Node, React, PostgreSQL,
                    Web3 and DApps.</div>
                <div class="all-rating">4.7 ★ (448,992)</div>
                <div class="all-tags"><span class="premium">Premium</span> <span class="bestseller">Bestseller</span>
                </div>
            </div>
            <div class="details-sec">
                <div class="all-price">₹5,109</div>
                <div class="addCart">
                    <button class="addCartbtn"><a href="#">Add To Cart</a></button>
                </div>
            </div>
        </div>


    </div>
    <div class="all-course-container" id="allCourseContainer">
        <?php while ($course = pg_fetch_assoc($coursesResult)): ?>
            <div class="all-course-card"
                data-category="<?= htmlspecialchars($course['category']) ?>">

                <img src="<?= htmlspecialchars($course['thumbnail']) ?>"
                    alt="Course Image" class="all-course-img">

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
                            onclick="addToCart(
                        '<?= htmlspecialchars($course['title']) ?>',
                        <?= $course['price'] ?>,
                        '<?= htmlspecialchars($course['thumbnail']) ?>',
                        'course-details.php?id=<?= $course['id'] ?>'
                    )">
                            Add To Cart
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
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
    <script src="userLoginScript.js"></script>
</body>

</html>