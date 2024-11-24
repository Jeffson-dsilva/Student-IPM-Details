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
    </div>
<div class="user-info" id="user-info">
  <p>Name: Canvil Joyal Lobo</p>
  <p>USN: 1234568910111</p>
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
      <form id="project-form" action="thankYou.php" method="POST" enctype="multipart/form-data" novalidate>
        <div class="star-mean">* Indicates required question</div>
        
        <div class="form-group">
          <label for="name">Name <span class="required">*</span></label>
          <input id="name" name="name" placeholder="Enter your full name" type="text"/>
          <div class="error" id="name-error"></div>
        </div>

        <div class="form-group">
          <label for="role">Project Role <span class="required">*</span></label>
          <input id="role" name="role" placeholder="Describe your role in the project" type="text"/>
          <div class="error" id="role-error"></div>
        </div>

        <div class="form-group">
          <label for="phone">Contact No <span class="required">*</span></label>
          <input type="number" id="phone" name="phone" placeholder="Provide your contact number"/>
          <div class="error" id="phone-error"></div>
        </div>

        <div class="form-group">
          <label for="location">Location <span class="required">*</span></label>
          <input id="location" name="location" placeholder="Enter the project location (if applicable)" type="text"/>
          <div class="error" id="location-error"></div>
        </div>

        <div class="form-group">
          <label for="start-date">Start Date <span class="required">*</span></label>
          <input id="start-date" name="start-date" type="date"/>
          <div class="error" id="start-date-error"></div>
        </div>

        <div class="form-group">
          <label for="end-date">End Date <span class="required">*</span></label>
          <input id="end-date" name="end-date" type="date"/>
          <div class="error" id="end-date-error"></div> 
        </div>

        <div class="form-group">
          <label for="languages-used">Languages/Technologies Used <span class="required">*</span></label>
          <input id="languages-used" name="languages-used" placeholder="List languages and technologies used (e.g., Java, Python, MySQL)" type="text"/>
          <div class="error" id="languages-used-error"></div>
        </div>

        <div class="form-group">
          <label for="project-title">Project Title <span class="required">*</span></label>
          <input id="project-title" name="project-title" placeholder="Enter the title of your project" type="text"/>
          <div class="error" id="project-title-error"></div>
        </div>

        <div class="form-group">
          <label for="project-domain">Project Domain <span class="required">*</span></label>
          <input id="project-domain" name="project-domain" placeholder="Specify the domain (e.g., Web Development, Data Science)" type="text"/>
          <div class="error" id="project-domain-error"></div>
        </div>

        <div class="form-group">
          <label for="project-description">Project Description <span class="required">*</span></label>
          <textarea id="project-description" name="project-description" placeholder="Provide a concise description of the project objectives and outcomes" rows="4" style="width: 100%;"></textarea>
          <div class="error" id="project-description-error"></div>
        </div>

        <div class="form-group">
          <label for="features">Project Features/Modules <span class="required">*</span></label>
          <textarea id="features" name="features" placeholder="List the main features or modules developed (e.g., User Authentication, Data Visualization)" rows="4" style="width: 100%;"></textarea>
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

        <!-- File Upload for Certificate -->
        <div class="form-group">
          <label for="certificate">Upload File <span class="required">*</span></label>
          <input id="certificate" name="certificate" type="file" accept=".pdf,.doc,.docx,.jpg,.png" onchange="validateFile(this)" />
          <small>Allowed file formats: PDF, DOC, DOCX, JPG, PNG. Max size: 200KB.</small>
          <div id="file-error" class="error" style="display: none;"></div>
        </div>

        <div class="submit-button">
          <button type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div> 
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
  <i class="fas fa-check"></i>
  <h2>Thank You! Your submission has been received.</h2>
  <button onclick="closeModal()">Close</button>
</div>


<script>
// Dark mode toggle functionality
const toggleButton = document.getElementById('toggle-mode');
toggleButton.addEventListener('click', function () {
  document.body.classList.toggle('dark-mode');
  toggleButton.classList.toggle('fa-moon');
  toggleButton.classList.toggle('fa-sun');
});

// Toggle user info dropdown
document.getElementById('user-icon').addEventListener('click', function () {
  const userInfo = document.getElementById('user-info');
  userInfo.style.display = (userInfo.style.display === 'none' || userInfo.style.display === '') 
    ? 'block' 
    : 'none';
});

// Logout functionality
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

// Form validation and submission handling
document.getElementById('project-form').addEventListener('submit', function (event) {
  event.preventDefault(); // Prevent default form submission

  let isValid = true;

  // Clear any previous error messages
  clearErrors();

  // Define fields for validation
  const fields = [
    { id: 'name', errorId: 'name-error', message: 'Name is required' },
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

  // If form is valid, show success modal
  if (isValid) {
    document.getElementById('successModal').style.display = 'block';
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
