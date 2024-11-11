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
            <img src="logo.png" alt="College Logo" class="logo-img">
            <div class="logo-text">
                <h1>St. Joseph Engineering College</h1>
                <p>Vamanjoor, Mangalore - 575028</p>
            </div>
        </div>
    </header>
    <br>
    <main>
        <div class="container">
            <h2>Login</h2>
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="stDashboard.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" required onchange="toggleUSNField()">
                        <option value="Select Role">Select Role</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="admin">Admin</option>
                    </select>
                    <span class="error" id="roleError"></span>
                </div>
                <div class="form-group">
                    <!-- <div class=""name> -->
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    <span class="error" id="nameError"></span>
                </div>

                <!-- USN field, initially hidden -->
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
                    <!-- <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <img src="showPass.png" class="eye-icon" onclick="togglePasswordVisibility()" id="eyeIcon">
                    </div> -->

                    <label for="password">Password</label>
        <div class="password-container">
            <input type="password" id="password" placeholder="Enter your password">
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
            if (role === 'student') {
                usnField.style.display = 'block';
            } else {
                usnField.style.display = 'none';
            }
        }
    
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    
        function validateForm() {
            let valid = true;
            
            // Role validation
            const role = document.getElementById('role').value;
            const roleError = document.getElementById('roleError');
            roleError.innerText = '';
            
            if (role === 'Select Role') {
                roleError.innerText = 'Please select a role.';
                valid = false;
            }
            
            // Password validation
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
