<?php
session_start();

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in (i.e., email is stored in the session)
if (!isset($_SESSION['email'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Get the email from the session
$email = $_SESSION['email'];

// Fetch user data (name and USN) from the students table based on email
$query = "SELECT name, usn FROM students WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $usn);
$stmt->fetch();
$stmt->close();

// If no user is found, redirect to login page
if (!$name || !$usn) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="stDashboard.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" alt="College Logo" class="logo-img">
            <div class="logo-text">
                <h1>St. Joseph Engineering College</h1><br>
                <p>Vamanjoor, Mangalore - 575028</p>
            </div>
        </div>
    </header>

    <div class="navbar">
        <i class="fas fa-bars" id="menu-icon"></i>
        <i class="fas fa-user" id="user-icon"></i>
        <i class="fas fa-moon dark-mode-toggle" id="toggle-mode"></i>
    </div>

    <div class="dropdown-menu" id="dropdown-menu">
        <a href="stupdatePassword.php"><i class="fas fa-key"></i> Update Password</a>
        <a href="internship.php"><i class="fas fa-briefcase"></i> Internship Details</a>
        <a href="project.php"><i class="fas fa-project-diagram"></i> Project Details</a>
        <a href="course.php"><i class="fas fa-book-open"></i> MOOC Course Details</a>
    </div>

    <div class="user-info" id="user-info">
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>USN: <?php echo htmlspecialchars($usn); ?></p>
        <button onclick="logout()">Logout</button>
    </div>

    <div class="container">
        <h1>Student Dashboard</h1><br><br>
        <div class="button-container">
            <button class="dashboard-button" onclick="window.location.href='internship.php'">
                <img src="images/internship.png" alt="Internship" class="button-logo">
                <b><span>INTERNSHIP DETAILS</span></b>
            </button>
            <button class="dashboard-button" onclick="window.location.href='project.php'">
                <img src="images/project.png" alt="Project" class="button-logo">
                <b><span>PROJECT DETAILS</span></b>
            </button>
            <button class="dashboard-button" onclick="window.location.href='course.php'">
                <img src="images/courses.png" alt="Courses" class="button-logo">
                <b><span>MOOC COURSE DETAILS</span></b>
            </button>
        </div>
    </div>

    <script>
    // Apply dark mode based on local storage
    document.addEventListener('DOMContentLoaded', () => {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
            toggleButton.classList.remove('fa-moon');
            toggleButton.classList.add('fa-sun');
        }
    });

    const toggleButton = document.getElementById('toggle-mode');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const userInfo = document.getElementById('user-info');
    const menuIcon = document.getElementById('menu-icon');
    const userIcon = document.getElementById('user-icon');

    toggleButton.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');

        // Save dark mode state in local storage
        const isDarkMode = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);

        // Switch icon between sun and moon
        if (isDarkMode) {
            toggleButton.classList.remove('fa-moon');
            toggleButton.classList.add('fa-sun');
        } else {
            toggleButton.classList.remove('fa-sun');
            toggleButton.classList.add('fa-moon');
        }
    });

    userIcon.addEventListener('click', function (event) {
        event.stopPropagation();

        // Show user info and hide dropdown menu
        dropdownMenu.classList.remove('show');
        userInfo.style.display = userInfo.style.display === 'block' ? 'none' : 'block';
    });

    menuIcon.addEventListener('click', function (event) {
        event.stopPropagation();

        // Show dropdown menu and hide user info
        userInfo.style.display = 'none';
        dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function () {
        // Hide both dropdown menu and user info on page click
        dropdownMenu.classList.remove('show');
        userInfo.style.display = 'none';
    });

    function logout() {
        window.location.href = 'login.php'; // Redirect to logout page
    }
</script>


</body>
</html>
