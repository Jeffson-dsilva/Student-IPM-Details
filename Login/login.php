<?php
// Include the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port=3307;

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

session_start();  // Start session for login

$error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // If the role is 'student', we also need to validate the USN
    $usn = isset($_POST['usn']) ? $_POST['usn'] : '';

    // Validate role selection
    if ($role === "Select Role") {
        $error = "Please select a role.";
    } else {
        // Validate credentials against the database based on role
        if ($role === 'student') {
            // For students, check USN, email, and password
            $query = "SELECT * FROM students WHERE usn = ? AND email = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $usn, $email, $password);
        } else {
            // For faculty and HOD, check email and password
            $table = $role === 'faculty' ? 'faculty' : 'hod';
            $query = "SELECT * FROM $table WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $password);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Successful login, set session variable for student email
            if ($role === 'student') {
                $_SESSION['email'] = $email;  // Set session variable for student email
                header("Location: stDashboard.php");
                exit();
            } elseif ($role === 'faculty') {
                header("Location: ftDashboard.php");
                exit();
            } elseif ($role === 'hod') {
                header("Location: hoddashboard.php");
                exit();
            }
        } else {
            $error = "Email or Password incorrect.";
        }
        $stmt->close();
    }
}
?>

<!-- HTML login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>St. Joseph Engineering College Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="login.css">
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
    <main>
        <div class="container">
            <h2>Login</h2>
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" required onchange="toggleUSNField()">
                        <option value="Select Role">Select Role</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="hod">HOD</option>
                    </select>
                    <span class="error" id="roleError"></span>
                </div>
                <div class="form-group" id="usnField" style="display: none;">
                    <label for="usn">USN</label>
                    <input type="text" id="usn" name="usn" placeholder="Enter your USN">
                    <span class="error" id="usnError"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <span class="error" id="emailError"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <span class="error" id="passwordError"></span>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
    <script>
        function toggleUSNField() {
            const role = document.getElementById('role').value;
            const usnField = document.getElementById('usnField');
            usnField.style.display = role === 'student' ? 'block' : 'none';
        }

        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        function validateForm() {
            let valid = true;
            const role = document.getElementById('role').value;
            const roleError = document.getElementById('roleError');
            roleError.innerText = '';

            if (role === 'Select Role') {
                roleError.innerText = 'Please select a role.';
                valid = false;
            }

            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            emailError.innerText = '';
            const emailPattern = /^[a-zA-Z0-9._%+-]+@sjec\.ac\.in$/;

            if (!emailPattern.test(email)) {
                emailError.innerText = 'Please enter a valid SJEC email.';
                valid = false;
            }

            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            passwordError.innerText = '';

            if (!passwordRegex.test(password)) {
                passwordError.innerText = 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.';
                valid = false;
            }

            return valid;
        }
    </script>
</body>
</html>
