<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="stDashboard.css">
</head>
<body>
    <header>
            <div class="logo">
                <img src="logo.png" alt="College Logo" class="logo-img">
                <div class="logo-text">
                    <h1>St. Joseph Engineering College</h1><br>
                    <p>Vamanjoor, Mangalore - 575028</p>
                </div>
            </div>
        </header>
        
    <div class="navbar">
    <i class="fas fa-bars">
    </i>
    <i class="fas fa-user" id="user-icon">
    </i>
    <i class="fas fa-cog" id="toggle-mode">
    </i>
    </div>
    <div class="user-info" id="user-info">
    <p>
        Name: Canvil Joyal Lobo
    </p>
    <p>
        USN: 1234568910111
    </p>
    <button onclick="logout()">
        Logout
    </button>
    </div>

    <div class="container">
        <h1>Student Dashboard </h1><br><br>
        <div class="button-container">
        <button class="dashboard-button">
            <img src="internship.png" alt="Logo 1" class="button-logo">
            <b><span>INTERNSHIP DETAILS</span></b>
        </button>
        <button class="dashboard-button">
            <img src="project.png" alt="Logo 2" class="button-logo">
        <b>  <span>PROJECT DETAILS</span></b>
        </button>
        <button class="dashboard-button">
            <img src="courses.png" alt="Logo 3" class="button-logo">
            <b><span>MOOC COURSE DETAILS</span></b>
        </button>
        </div><br><br>
    </div>

    <script>
    document.getElementById('user-icon').addEventListener('click', function() {
                var userInfo = document.getElementById('user-info');
                if (userInfo.style.display === 'none' || userInfo.style.display === '') {
                    userInfo.style.display = 'block';
                } else {
                    userInfo.style.display = 'none';
                }
            });

            document.getElementById('toggle-mode').addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
            });

            function logout() {
                window.location.href = 'login.php';
            }
    </script>

</body>
</html>
