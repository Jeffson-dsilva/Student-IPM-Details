<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port = 3307;

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

// Initialize variables for error messages and success flag
$emailError = $newPasswordError = $confirmPasswordError = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $newPassword = trim($_POST['new-password']);
    $confirmPassword = trim($_POST['confirm-password']);

    // Validate email
    $stmt = $conn->prepare("SELECT email FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $emailError = "Incorrect email. Please enter a valid email.";
    }

    // Validate new password
    $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordRegex, $newPassword)) {
        $newPasswordError = "Password must be at least 8 characters, including uppercase, lowercase, number, and special character.";
    }

    // Validate confirm password
    if ($newPassword !== $confirmPassword) {
        $confirmPasswordError = "Passwords do not match.";
    }

    // If no errors, update password
    if (empty($emailError) && empty($newPasswordError) && empty($confirmPasswordError)) {
        $hashedPassword = $newPassword; 
        $updateStmt = $conn->prepare("UPDATE students SET password = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);

        if ($updateStmt->execute()) {
            $success = true;
        } else {
            $emailError = "Error updating password. Please try again later.";
        }

        $updateStmt->close();
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="updatePassword.css">
    
</head>
<body>
<header>
        <div class="logo">
            <img src="images/logo.png" alt="College Logo" class="logo-img">
            <div class="logo-text">
                <h1>St. Joseph Engineering College</h1>
                <p>Vamanjoor, Mangalore - 575028</p>
            </div>
        </div>
    </header>
    <div class="navbar">
    <i class="fas fa-bars" id="menu-icon"></i>
    <i class="fas fa-user" id="user-icon"></i>
    <i class="fas fa-moon dark-mode-toggle" id="toggle-mode"></i>
    </div>
    <!-- Dropdown Menu -->
    <div class="dropdown-menu" id="dropdown-menu">
        <a href="stdashboard.php"><i class="fas fa-home"></i>Home</a>
        
   
</div>



    <div class="user-info" id="user-info">
    <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>USN: <?php echo htmlspecialchars($usn); ?></p>
    <button onclick="logout()">
        Logout
    </button>
    </div>
    <br>
    <main>
        <div class="container">
            <h2>Update Password</h2>
            <form id="updatePasswordForm" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <?php if (!empty($emailError)): ?>
                        <span class="error"><?php echo $emailError; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <div class="password-container">
                        <input type="password" id="new-password" name="new-password" placeholder="Enter new password" >
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <?php if (!empty($newPasswordError)): ?>
                        <span class="error"><?php echo $newPasswordError; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password" >
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <?php if (!empty($confirmPasswordError)): ?>
                        <span class="error"><?php echo $confirmPasswordError; ?></span>
                    <?php endif; ?>
                </div>

                <button class="submit-button" type="submit">Save</button>
            </form>
        </div>
    </main>

    <!-- Success Modal -->
    <?php if ($success): ?>
        <div id="successModal" class="modal" style="display: flex;">
            <div class="modal-content">
                <i class="fas fa-check"></i>
                <h2>Password Updated Successfully!</h2>
                <button onclick="closeModal()">Close</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            icon.addEventListener('click', function () {
                const passwordField = this.previousElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Validate form fields
        function validateForm() {
            let valid = true;

            // Email validation for SJEC domain
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            emailError.innerText = '';
            const emailPattern = /^[a-zA-Z0-9._%+-]+@sjec\.ac\.in$/;

            if (!emailPattern.test(email)) {
                emailError.innerText = 'Please enter a valid SJEC email.';
                valid = false;
            }

            // New password validation
            const newPassword = document.getElementById('new-password').value;
            const newPasswordError = document.getElementById('newPasswordError');
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            newPasswordError.innerText = '';
            if (!passwordRegex.test(newPassword)) {
                newPasswordError.innerText = 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.';
                valid = false;
            }

            // Confirm password validation
            const confirmPassword = document.getElementById('confirm-password').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            confirmPasswordError.innerText = '';

            if (newPassword !== confirmPassword) {
                confirmPasswordError.innerText = 'Passwords do not match.';
                valid = false;
            }

            // Show success modal if validation is successful
            if (valid) {
                document.getElementById('successModal').style.display = 'flex';
                return false;  // Prevent form submission (stay on the same page)
            }

            return false;  // Prevent form submission if validation fails
        }

        // Close the modal
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
        document.getElementById('user-icon').addEventListener('click', function() {
                var userInfo = document.getElementById('user-info');
                if (userInfo.style.display === 'none' || userInfo.style.display === '') {
                    userInfo.style.display = 'block';
                } else {
                    userInfo.style.display = 'none';
                }
            });
/* MENU */
// Get the menu icon and dropdown menu elements
const menuIcon = document.getElementById('menu-icon');
const dropdownMenu = document.getElementById('dropdown-menu');

// Toggle the dropdown menu on icon click
menuIcon.addEventListener('click', function(event) {
    event.stopPropagation(); // Prevent click from bubbling up
    dropdownMenu.classList.toggle('show');
});

// Close the dropdown menu when clicking outside
document.addEventListener('click', function(event) {
    if (!dropdownMenu.contains(event.target) && !menuIcon.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});


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



            function logout() {
                window.location.href = 'login.php';
            }

    </script>

</body>
</html>
