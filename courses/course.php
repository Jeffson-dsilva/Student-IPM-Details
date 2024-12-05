<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    header("Location: login.php"); 
    exit();
}

$email = $_SESSION['email'];

$query = "SELECT name, usn FROM students WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $usn);
$stmt->fetch();
$stmt->close();

if (!$name || !$usn) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and fetch form data
    $name = trim($_POST['name']);
    $usn = trim($_POST['USN']);
    $start_date = trim($_POST['start-date']);
    $end_date = trim($_POST['end-date']);

    $uploaded_file = null;
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
        $uploaded_file = file_get_contents($_FILES['certificate']['tmp_name']);
    }

    $stmt = $conn->prepare(
        "INSERT INTO mooc_courses (name, usn, start_date, end_date, certificate) 
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $usn, $start_date, $end_date, $uploaded_file);

    if ($stmt->execute()) {
        header("Location: course.php?submitted=true");
        exit();
    } else {
        echo "<script>alert('Error saving course details: " . htmlspecialchars($stmt->error) . "');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOC Course Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="project.css">
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
    <a href="stDashboard.php"><i class="fas fa-home"></i>Home</a>
    <a href="internship.php"><i class="fas fa-briefcase"></i> Internship Details</a>
    <a href="project.php"><i class="fas fa-project-diagram"></i> Project Details</a>
    <a href="course.php"><i class="fas fa-book-open"></i> MOOC Course Details</a>
</div>

<div class="user-info" id="user-info">
    <p>Name: <?php echo htmlspecialchars($name); ?></p>
    <p>USN: <?php echo htmlspecialchars($usn); ?></p>
    <button onclick="logout()">Logout</button>
</div>

<div class="body-container">
    <div class="container">
        <div class="img-container">
            <div class="image">
                <img alt="MOOC Courses" height="200" src="images/mooc.jpeg"/>
            </div>
        </div>
        <div class="form-section">
            <form action="course.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
                <div class="form-group">
                    <span class="required">* Indicates required question</span>
                </div>
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" name="name" placeholder="Enter your first and last name" type="text" />
                    <div class="error" id="nameError"></div>
                </div>
                <div class="form-group">
                    <label for="USN">USN <span class="required">*</span></label>
                    <input id="USN" name="USN" placeholder="Enter your USN" type="text" />
                    <div class="error" id="USNError"></div>
                </div>
                <div class="form-group">
                    <label for="start-date">Start Date(Expected) <span class="required">*</span></label>
                    <input id="start-date" name="start-date" type="date" />
                    <div class="error" id="startDateError"></div>
                </div>
                <div class="form-group">
                    <label for="end-date">End Date(Expected) <span class="required">*</span></label>
                    <input id="end-date" name="end-date" type="date" />
                    <div class="error" id="endDateError"></div>
                </div>
                <div class="form-group">
                    <label for="certificate">Certificate <span class="required">*</span></label>
                    <input id="certificate" name="certificate" type="file" accept=".pdf,.doc,.docx,.jpg,.png" onchange="validateFile(this)" />
                    <small>Allowed file formats: PDF,JPG, PNG.. Max size: 200KB.</small>
                    <div class="error" id="fileError"></div>
                </div>
                <div class="submit-button">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['submitted']) && $_GET['submitted'] == 'true'): ?>
    <div id="successModal" class="modal" style="display: block;">
        <i class="fas fa-check"></i>
        <h2>Thank You! Your submission has been received.</h2>
        <button onclick="closeModal()">Close</button>
    </div>
<?php endif; ?>

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
        
        toggleButton.addEventListener('click', function() {
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

// Logout functionality
function logout() {
    window.location.href = 'login.php';
}

    function validateForm(event) {
        event.preventDefault();
        let isValid = true;
        clearErrors();

        const name = document.getElementById('name').value.trim();
        if (name === '') {
            isValid = false;
            document.getElementById('nameError').textContent = 'Name is required.';
        }

        const USN = document.getElementById('USN').value.trim();
        if (USN === '') {
            isValid = false;
            document.getElementById('USNError').textContent = 'USN is required.';
        }

        const startDate = document.getElementById('start-date').value;
        if (startDate === '') {
            isValid = false;
            document.getElementById('startDateError').textContent = 'Start date is required.';
        }

        const endDate = document.getElementById('end-date').value;
        if (endDate === '') {
            isValid = false;
            document.getElementById('endDateError').textContent = 'End date is required.';
        } else if (startDate && new Date(startDate) > new Date(endDate)) {
            isValid = false;
            document.getElementById('endDateError').textContent = 'End date cannot be earlier than start date.';
        }

        if (isValid) {
            event.target.submit();
        }
    }

    function validateFile(fileInput) {
        const file = fileInput.files[0];
        const fileError = document.getElementById('fileError');
        fileError.textContent = '';

        if (file) {
            const fileSize = file.size / 1024; // in KB
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.png'];
            const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

            if (allowedExtensions.indexOf(fileExtension) === -1) {
                fileError.textContent = 'Invalid file type. Please upload a PDF, DOC, DOCX, JPG, or PNG.';
                fileInput.value = '';
            } else if (fileSize > 200) {
                fileError.textContent = 'File size exceeds the 200KB limit.';
                fileInput.value = '';
            }
        }
    }

    function clearErrors() {
        document.getElementById('nameError').textContent = '';
        document.getElementById('USNError').textContent = '';
        document.getElementById('startDateError').textContent = '';
        document.getElementById('endDateError').textContent = '';
        document.getElementById('fileError').textContent = '';
    }

    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }
</script>

</body>
</html>
