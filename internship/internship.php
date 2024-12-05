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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT name, usn FROM students WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($userName, $usn);
$stmt->fetch();
$stmt->close();

// Redirect to login if user not found
if (!$userName || !$usn) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize inputs
  $internName = $conn->real_escape_string($_POST['name']);
  $usn = $conn->real_escape_string($_POST['usn']);
  $role = $conn->real_escape_string($_POST['role']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $location = $conn->real_escape_string($_POST['location']);
  $start_date = $conn->real_escape_string($_POST['start-date']);
  $end_date = $conn->real_escape_string($_POST['end-date']);
  $languages_used = $conn->real_escape_string($_POST['working-on']);

  // Handle file upload
  $certificate = $_FILES['certificate'];
  $certificateContent = file_get_contents($certificate['tmp_name']);

  // Insert data into the internship table, including binary certificate
  $sql = "INSERT INTO internship (name, usn, role, phone, location, start_date, end_date, languages_used, certificate)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
      "sssssssss",
      $internName,
      $usn,
      $role,
      $phone,
      $location,
      $start_date,
      $end_date,
      $languages_used,
      $certificateContent
  );

  if ($stmt->execute()) {
      echo '<script>alert("Internship details saved successfully!");</script>';
      header("Location: internship.php?submitted=true");
      exit();
  } else {
      echo '<script>alert("Database Error: ' . $conn->error . '");</script>';
  }

  $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internship Details</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="internship.css"> 
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
    <p>Name: <?php echo htmlspecialchars($userName); ?></p>
    <p>USN: <?php echo htmlspecialchars($usn); ?></p>
    <button onclick="logout()">Logout</button>
</div>

<div class="body-container">
  <div class="container">
    <div class="img-container">
      <div class="image">
        <img alt="Internship" height="200" src="images/intership.jpeg" width="600"/>
      </div>
    </div>

    <div class="form-section">
      <form id="applicationForm" action="internship.php" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(event)">
        <div class="star-mean">
          * Indicates required question
        </div>

        <!-- Input fields with validations -->
        <div class="form-group">
          <label for="name">Name <span class="required">*</span></label>
          <input id="name" name="name" placeholder="Enter your first and last name" type="text"/>
          <div class="error-message" id="nameError"></div>
        </div>

        <div class="form-group">
          <label for="name">USN <span class="required">*</span></label>
          <input id="usn" name="usn" placeholder="Enter your USN" type="text"/>
          <div class="error-message" id="usnError"></div>
        </div>

        <div class="form-group">
          <label for="role">Role <span class="required">*</span></label>
          <input id="role" name="role" placeholder="Enter your internship role" type="text"/>
          <div class="error-message" id="roleError"></div>
        </div>

        <div class="form-group">
          <label for="phone">Contact No <span class="required">*</span></label>
          <input type="number" name="phone" placeholder="Enter contact number of internship" id="phone" type="tel"/>
          <div class="error-message" id="phoneError"></div>
        </div>

        <div class="form-group">
          <label for="location">Location <span class="required">*</span></label>
          <input id="location" name="location" placeholder="Enter the location of internship" type="text"/>
          <div class="error-message" id="locationError"></div>
        </div>

        <div class="form-group">
          <label for="start-date">Start Date <span class="required">*</span></label>
          <input id="start-date" name="start-date" type="date"/>
          <div class="error-message" id="startDateError"></div>
        </div>

        <div class="form-group">
          <label for="end-date">End Date <span class="required">*</span></label>
          <input id="end-date" name="end-date" type="date"/>
          <div class="error-message" id="endDateError"></div>
        </div>

        <div class="form-group">
          <label for="working-on">Languages Used <span class="required">*</span></label>
          <input id="working-on" name="working-on" placeholder="Enter languages used" type="text"/>
          <div class="error-message" id="workingOnError"></div>
        </div>

        <div class="form-group">
          <label for="certificate">Certificate <span class="required">*</span></label>
          <input id="certificate" name="certificate" type="file" accept=".pdf,.doc,.docx,.jpg,.png" onchange="validateFile(this)" />
          <small>Allowed file formats: PDF,JPG, PNG. Max size: 200KB.</small>
          <div id="file-error" class="error-message"></div>
        </div>



        
        <div class="submit-button">
          <button type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Success Modal -->
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

// File validation for certificate upload
function validateFile(input) {
  const file = input.files[0];
  const fileError = document.getElementById('file-error');
  const validTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
  const maxSize = 200 * 1024; // 200KB

  if (file) {
    const fileExtension = file.name.split('.').pop().toLowerCase();
    if (!validTypes.includes(fileExtension)) {
      fileError.textContent = 'Invalid file type. Please upload a valid file (PDF, DOC, DOCX, JPG, PNG).';
      input.value = ''; // Clear the input
    } else if (file.size > maxSize) {
      fileError.textContent = 'File size exceeds the maximum limit of 200KB.';
      input.value = ''; // Clear the input
    } else {
      fileError.textContent = ''; // Clear error message
    }
  }
}

// Form validation function
function checkForm(event) {
  let isValid = true;

  // Clear previous errors
  document.querySelectorAll('.error-message').forEach(function (el) {
    el.textContent = '';
  });

  // Name validation
  const name = document.getElementById('name').value.trim();
  if (name === '') {
    isValid = false;
    document.getElementById('nameError').textContent = 'Name is required.';
  }

// USN validation
  const usn = document.getElementById('usn').value.trim();
  if (usn === '') {
    isValid = false;
    document.getElementById('usnError').textContent = 'USN is required.';
  }

  // Role validation
  const role = document.getElementById('role').value.trim();
  if (role === '') {
    isValid = false;
    document.getElementById('roleError').textContent = 'Role is required.';
  }

  // Phone number validation
  const phone = document.getElementById('phone').value.trim();
  const phoneRegex = /^[0-9]{10}$/;
  if (phone === '') {
    document.getElementById('phoneError').textContent = 'Contact number is required.';
    isValid = false;
  } else if (!phoneRegex.test(phone)) {
    document.getElementById('phoneError').textContent = 'Enter a valid 10-digit phone number.';
    isValid = false;
  }

  // Location validation
  const location = document.getElementById('location').value.trim();
  if (location === '') {
    isValid = false;
    document.getElementById('locationError').textContent = 'Location is required.';
  }

  // Start date validation
  const startDate = document.getElementById('start-date').value.trim();
  if (startDate === '') {
    document.getElementById('startDateError').textContent = 'Start date is required.';
    isValid = false;
  }

  // End date validation
  const endDate = document.getElementById('end-date').value.trim();
  if (endDate === '') {
    document.getElementById('endDateError').textContent = 'End date is required.';
    isValid = false;
  } else if (startDate && new Date(startDate) > new Date(endDate)) {
    document.getElementById('endDateError').textContent = 'End date must be after start date.';
    isValid = false;
  }

  // Working on languages validation
  const languagesUsed = document.getElementById('working-on').value.trim();
  if (languagesUsed === '') {
    isValid = false;
    document.getElementById('workingOnError').textContent = 'Languages used is required.';
  }

  // File validation
  const certificate = document.getElementById('certificate').files[0];
  if (!certificate) {
    isValid = false;
    document.getElementById('file-error').textContent = 'Certificate file is required.';
  }

  if (!isValid) {
    event.preventDefault();
  }

  return isValid;
}

function closeModal() {
  document.getElementById('successModal').style.display = 'none';
}
</script>
</body>
</html>
