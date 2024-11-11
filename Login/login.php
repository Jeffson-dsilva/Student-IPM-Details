<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>St. Joseph Engineering College Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo1.jpeg" alt="College Logo" class="logo-img">
            <div class="logo-text">
                <h1>St. Joseph Engineering College</h1>
                <p>Vamanjoor, Mangalore - 575028</p>
            </div>
        </div>
    </header>
    <br>
    <main>
        <div class="container">
            <!-- <div class="registration-form"> -->
            <h2>Login</h2>

                <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

                <form action="login_process.php" method="POST">
                    <div class="form-group">
                        <label for="role">Select Role</label>
                        <select id="role" name="role" required>
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="admin">Admin</option>
                        </select>
                        <span class="error" id="roleError"></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" id="name" name="name" placeholder="Enter your name" required>
                        <span class="error" id="emailError"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <span class="error" id="emailError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span class="error" id="passwordError"></span>
                    </div>

                    <button type="submit">Login</button>
                    
                </form>
            <!-- </div> -->
        </div>
    </main>

    <script>
        // Basic validation
        document.querySelector('form').addEventListener('submit', function(event) {
            let valid = true;
            const role = document.getElementById('role').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!role) {
                document.getElementById('roleError').innerText = 'Role is required';
                valid = false;
            }
            if (!email) {
                document.getElementById('emailError').innerText = 'Email is required';
                valid = false;
            }
            if (!password) {
                document.getElementById('passwordError').innerText = 'Password is required';
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

