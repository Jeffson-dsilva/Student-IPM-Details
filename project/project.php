<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
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

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user data
$query = "SELECT name, usn FROM students WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $usn);
$stmt->fetch();
$stmt->close();

// Redirect if no user data is found
if (!$name || !$usn) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and fetch form data
    $name= trim($_POST['name']);
    $usn= trim($_POST['usn']);
    $project_role = trim($_POST['role']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $start_date = trim($_POST['start-date']);
    $end_date = trim($_POST['end-date']);
    $languages_used = trim($_POST['languages-used']);
    $project_title = trim($_POST['project-title']);
    $project_domain = trim($_POST['project-domain']);
    $project_description = trim($_POST['project-description']);
    $features = trim($_POST['features']);
    $problem_statement = trim($_POST['problem-statement']);
    $proposed_solution = trim($_POST['solution']);
    $github_link = trim($_POST['github-link']);
    
    // Handle file upload (as binary data)
    $uploaded_file_content = null;
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
        $uploaded_file_content = file_get_contents($_FILES['certificate']['tmp_name']); // Read file content
    }

    // Prepare SQL statement
    $stmt = $conn->prepare(
        "INSERT INTO project (name, usn, project_role, phone, location, start_date, end_date, 
        languages_used, project_title, project_domain, project_description, features, 
        problem_statement, proposed_solution, github_link, uploaded_file_name, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters (binary data for uploaded_file_name)
    $stmt->bind_param(
        "ssssssssssssssss",
        $name,
        $usn,
        $project_role,
        $phone,
        $location,
        $start_date,
        $end_date,
        $languages_used,
        $project_title,
        $project_domain,
        $project_description,
        $features,
        $problem_statement,
        $proposed_solution,
        $github_link,
        $uploaded_file_content
    );

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: project.php?submitted=true");
        exit();
    } else {
        echo "<script>alert('Error saving project details: " . htmlspecialchars($stmt->error) . "');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
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
                <img alt="Project" height="200" src="images/project.jpeg" width="600"/>
            </div>
        </div>

        <div class="form-section">
            <form id="project-form" action="project.php" method="POST" enctype="multipart/form-data" novalidate>
                <div class="star-mean">* Indicates required question</div>
                
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" name="name" placeholder="Enter your full name" type="text" required/>
                    <div class="error" id="name-error"></div>
                </div>

                <div class="form-group">
                    <label for="usn">USN <span class="required">*</span></label>
                    <input id="usn" name="usn" placeholder="Enter your usn" type="text" required/>
                    <div class="error" id="usn-error"></div>
                </div>

    
                <div class="form-group">
                    <label for="role">Project Role <span class="required">*</span></label>
                    <input id="role" name="role" placeholder="Describe your role in the project" type="text" required/>
                    <div class="error" id="role-error"></div>
                </div>

                <div class="form-group">
                    <label for="phone">Contact No <span class="required">*</span></label>
                    <input type="number" id="phone" name="phone" placeholder="Provide your contact number" required/>
                    <div class="error" id="phone-error"></div>
                </div>

                <div class="form-group">
                    <label for="location">Location <span class="required">*</span></label>
                    <input id="location" name="location" placeholder="Enter the project location (if applicable)" type="text" required/>
                    <div class="error" id="location-error"></div>
                </div>

                <div class="form-group">
                    <label for="start-date">Start Date <span class="required">*</span></label>
                    <input id="start-date" name="start-date" type="date" required/>
                    <div class="error" id="start-date-error"></div>
                </div>

                <div class="form-group">
                    <label for="end-date">End Date <span class="required">*</span></label>
                    <input id="end-date" name="end-date" type="date" required/>
                    <div class="error" id="end-date-error"></div> 
                </div>

                <div class="form-group">
                    <label for="languages-used">Languages/Technologies Used <span class="required">*</span></label>
                    <input id="languages-used" name="languages-used" placeholder="List languages and technologies used (e.g., Java, Python, MySQL)" type="text" required/>
                    <div class="error" id="languages-used-error"></div>
                </div>

                <div class="form-group">
                    <label for="project-title">Project Title <span class="required">*</span></label>
                    <input id="project-title" name="project-title" placeholder="Enter the title of your project" type="text" required/>
                    <div class="error" id="project-title-error"></div>
                </div>

                <div class="form-group">
                    <label for="project-domain">Project Domain <span class="required">*</span></label>
                    <input id="project-domain" name="project-domain" placeholder="Specify the domain (e.g., Web Development, Data Science)" type="text" required/>
                    <div class="error" id="project-domain-error"></div>
                </div>

                <div class="form-group">
                    <label for="project-description">Project Description <span class="required">*</span></label>
                    <textarea id="project-description" name="project-description" placeholder="Provide a concise description of the project objectives and outcomes" rows="4" style="width: 100%;" required></textarea>
                    <div class="error" id="project-description-error"></div>
                </div>

                <div class="form-group">
                    <label for="features">Project Features/Modules <span class="required">*</span></label>
                    <textarea id="features" name="features" placeholder="List the main features or modules developed (e.g., User Authentication, Data Visualization)" rows="4" style="width: 100%;" required></textarea>
                    <div class="error" id="features-error"></div>
                </div>

                <div class="form-group">
                    <label for="problem-statement">Problem Statement <span class="required">*</span></label>
                    <textarea id="problem-statement" name="problem-statement" placeholder="Describe the problem the project addresses" rows="4" style="width: 100%;" required></textarea>
                    <div class="error" id="problem-statement-error"></div>
                </div>

                <div class="form-group">
                    <label for="solution">Proposed Solution <span class="required">*</span></label>
                    <textarea id="solution" name="solution" placeholder="Explain the solution provided by the project" rows="4" style="width: 100%;" required></textarea>
                    <div class="error" id="solution-error"></div>
                </div>

                <div class="form-group">
                    <label for="github-link">Source Code Repository</label>
                    <input id="github-link" name="github-link" placeholder="Provide the GitHub link (if available)" type="url"/>
                </div>

                <div class="form-group">
                    <label for="certificate">Upload File <span class="required">*</span></label>
                    <input id="certificate" name="certificate" type="file" accept=".pdf,.doc,.docx,.jpg,.png" required/>
                    <small>Allowed file formats: PDF,JPG, PNG.. Max size: 200KB.</small>
                    <div id="file-error" class="error"></div>
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



// Form validation and submission handling
document.getElementById('project-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    let isValid = true;

    // Clear any previous error messages
    clearErrors();

    // Define fields for validation
    const fields = [
        { id: 'name', errorId: 'name-error', message: 'Name is required' },
        { id: 'usn', errorId: 'usn-error', message: 'usn is required' },
        { id: 'role', errorId: 'role-error', message: 'Project role is required' },
        { id: 'phone', errorId: 'phone-error', message: 'Contact number is required' },
        { id: 'location', errorId: 'location-error', message: 'Location is required' },
        { id: 'start-date', errorId: 'start-date-error', message: 'Start date is required' },
        { id: 'end-date', errorId: 'end-date-error', message: 'End date is required' },
        { id: 'languages-used', errorId: 'languages-used-error', message: 'Languages/Technologies used are required' },
        { id: 'project-title', errorId: 'project-title-error', message: 'Project title is required' },
        { id: 'project-domain', errorId: 'project-domain-error', message: 'Project domain is required' },
        { id: 'project-description', errorId: 'project-description-error', message: 'Project description is required' },
        { id: 'features', errorId: 'features-error', message: 'Project features are required' },
        { id: 'problem-statement', errorId: 'problem-statement-error', message: 'Problem statement is required' },
        { id: 'solution', errorId: 'solution-error', message: 'Solution is required' },
        { id: 'certificate', errorId: 'file-error', message: 'File upload is required' },
    ];

    // Validate each field
    fields.forEach(function (field) {
        const input = document.getElementById(field.id);
        const errorElement = document.getElementById(field.errorId);
        if (!input.value.trim()) {
            errorElement.textContent = field.message;
            isValid = false;
        } else {
            errorElement.textContent = ''; // Clear error if valid
        }
    });

    // Validate contact number format
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const phoneRegex = /^[0-9]{10}$/;

    if (!phoneRegex.test(phoneInput.value.trim())) {
        phoneError.textContent = 'Contact number must be a 10-digit number';
        isValid = false;
    } else {
        phoneError.textContent = ''; // Clear error if valid
    }

    // Validate date order
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    const endDateErrorElement = document.getElementById('end-date-error');

    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
        endDateErrorElement.textContent = 'End date cannot be earlier than start date';
        isValid = false;
    } else {
        endDateErrorElement.textContent = ''; // Clear error if dates are valid
    }

    // If form is valid, submit the form
    if (isValid) {
        this.submit(); // Submit the form
    }
});

// Close success modal and reset form
function closeModal() {
    document.getElementById('successModal').style.display = 'none';
    document.getElementById('project-form').reset(); // Reset form fields
}

// Clear error messages
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error');
    errorMessages.forEach(msg => msg.textContent = '');
}

// Validate file upload
function validateFile(input) {
    const file = input.files[0];
    if (file && file.size > 200000) { // 200KB size limit
        const fileError = document.getElementById('file-error');
        fileError.textContent = 'File size exceeds 200KB';
        fileError.style.display = 'block';
        input.value = ''; // Clear the file input
    } else {
        document.getElementById('file-error').style.display = 'none';
    }
}
</script>

</body>
</html>
