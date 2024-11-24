<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>HOD Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="hodDashboard.css">
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
    <i class="fas fa-bars" id="menu-icon">
    </i>
    <i class="fas fa-user" id="user-icon">
    </i>
    <i class="fas fa-moon dark-mode-toggle" id="toggle-mode">
    </i>
    
    
    </div>
    <div class="dropdown-menu" id="dropdown-menu">
        <a href="updatePassword.php"><i class="fas fa-key"></i>Update Password</a>
        <a href="uploadFile.php"><i class="fas fa-upload"></i> Upload File</a>
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
        <h1>HOD Dashboard </h1><br><br>
        <div class="button-container">
        <button class="dashboard-button">
            <img src="images/internship.png" alt="Logo 1" class="button-logo">
            <b><span>VIEW INTERNSHIP DETAILS</span></b>
           
        </button>
        <button class="dashboard-button">
            <img src="images/project.png" alt="Logo 2" class="button-logo">
        <b>  <span>VIEW PROJECT DETAILS</span></b>
        </button>
        <button class="dashboard-button">
            <img src="images/courses.png" alt="Logo 3" class="button-logo">
            <b><span>VIEW MOOC COURSE DETAILS</span></b>
        </button>
        </div><br><br>
    </div>

    <script>

         // Dark mode toggle functionality
       const toggleButton = document.getElementById('toggle-mode');
        
        toggleButton.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            // Switch icon between sun and moon
            if (document.body.classList.contains('dark-mode')) {
                toggleButton.classList.remove('fa-moon');
                toggleButton.classList.add('fa-sun');
            } else {
                toggleButton.classList.remove('fa-sun');
                toggleButton.classList.add('fa-moon');
            }
        });


    document.getElementById('user-icon').addEventListener('click', function() {
                var userInfo = document.getElementById('user-info');
                if (userInfo.style.display === 'none' || userInfo.style.display === '') {
                    userInfo.style.display = 'block';
                } else {
                    userInfo.style.display = 'none';
                }
            });

          

            function logout() {
                window.location.href = 'login.php';
            }

            
            
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
    </script>

</body>
</html>
