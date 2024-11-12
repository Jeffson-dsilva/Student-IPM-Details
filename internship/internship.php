<html>
 <head>
  <title>
   Job Application Form
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="internship.css">
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

            <div class="body-container">  
        <div class="container">
        <div class="image">
            <img alt="Internship" height="200" src="internships.jpeg" width="600"/>
        </div>
    </div>
    
    <div class="form-section">
    <form action="thankYou.php" method="POST" enctype="multipart/form-data">
    <div class="star-mean">
   * Indicates required question
      
      </div>
    <div class="form-group">
     <label for="name">
      Name
      <span class="required">
       *
      </span>
     </label>
     <input id="name" name="name" placeholder="Enter your first and last name" type="text"/>
    </div>
    <div class="form-group">
     <label for="role">
      Role
      <span class="required">
       *
      </span>
     </label>
     <input id="role" name="role" placeholder="Enter your internship role" type="text"/>
    </div>
    <div class="form-group">
     <label for="phone">
     Contact No
      <span class="required">
       *
      </span>
     </label>
     <input type="number" name="phone" placeholder="Enter contact number of intership" type="tel"/>
    </div>
    <div class="form-group">
     <label for="phone">
     Location
      <span class="required">
       *
      </span>
     </label>
     <input id="phone" name="phone" placeholder="Enter the Location of  intership" type="text"/>
    </div>
    <div class="form-group">
     <label for="start-date">
      Start Date
      <span class="required">
       *
      </span>
     </label>
     <input id="start-date" name="start-date" type="date"/>
    </div>
    <div class="form-group">
     <label for="end-date">
      End Date
      <span class="required">
       *
      </span>
     </label>
     <input id="end-date" name="end-date" type="date"/>
    </div>
    <div class="form-group">
     <label for="working-on">
  Languages Used
  <span class="required">
       *
      </span>
     </label>
     <input id="working-on" name="working-on" placeholder="Enter your languages used" type="text"/>
    </div>
    <div class="form-group">
  <label for="certificate">
    Certificate
    <span class="required">
      *
    </span>
  </label>
  <input id="certificate" name="certificate" type="file" accept=".pdf,.doc,.docx,.jpg,.png" onchange="validateFile(this)" />
  <small>Allowed file formats: PDF, DOC, DOCX, JPG, PNG. Max size: 200KB.</small>
  <div id="file-error" style="color: red; display: none;"></div> 
</div>


    <div class="submit-button">
     <button type="submit" >
      Submit
     </button>
    </div>
   </div>
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




            function validateFile(input) {
    const file = input.files[0];
    const maxSize = 200 * 1024; // 200KB in bytes
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
    const errorMessage = document.getElementById('file-error');
    
    if (file) {
      // Check if the file is within the size limit
      if (file.size > maxSize) {
        errorMessage.textContent = 'File is too large. Maximum size is 200KB.';
        errorMessage.style.display = 'block';
        input.value = ''; // Clear the input
        return false;
      }
      
      // Check if the file type is allowed
      if (!allowedTypes.includes(file.type)) {
        errorMessage.textContent = 'Invalid file type. Only PDF, DOC, DOCX, JPG, PNG are allowed.';
        errorMessage.style.display = 'block';
        input.value = ''; // Clear the input
        return false;
      }

      // Clear error message if the file is valid
      errorMessage.style.display = 'none';
    }
    
    return true;
  }
    </script>
 </body>
</html>

