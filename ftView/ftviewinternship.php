<?php
// Include database connection
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

// Fetching data from the internship table
$sql = "SELECT name, usn FROM internship";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ftviewinternship.css">
    <title>Internship Table</title>
  
    <script>
        // Function to validate checkbox selection
        function validateSelection(event) {
            const checkboxes = document.querySelectorAll('.checkbox');
            const errorMessage = document.getElementById('errorMessage');
            let isChecked = false;

            // Check if any checkbox is selected
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });

            // Show error if no checkbox is selected
            if (!isChecked) {
                event.preventDefault(); // Prevent form submission
                errorMessage.style.display = 'block'; // Show error message
            }
        }

        // Function to hide error message when a checkbox is clicked
        function hideError() {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'none'; // Hide error message
        }

        // Attach event listeners
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('checkboxForm');
            const checkboxes = document.querySelectorAll('.checkbox');

            // Attach the validation function to the form's submit event
            form.addEventListener('submit', validateSelection);

            // Attach the hideError function to each checkbox
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('click', hideError);
            });
        });

        // Function to filter table based on search input
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('internshipTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
                const columns = rows[i].getElementsByTagName('td');
                const name = columns[1].textContent.toLowerCase(); // Get the name column
                const usn = columns[0].textContent.toLowerCase(); // Get the USN column

                if (name.includes(searchInput) || usn.includes(searchInput)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by Name or USN" onkeyup="filterTable()" class="search-input">
            <button type="button" class="search-button" onclick="filterTable()">Search</button>
        </div>

        <!-- Form submission to ftinternship.php -->
        <form id="checkboxForm" action="ftinternship.php" method="POST">
            <table id="internshipTable">
                <thead>
                    <tr>
                        <th>USN</th>
                        <th>Name</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["usn"]) . "</td>
                                    <td>" . htmlspecialchars($row["name"]) . "</td>
                                    <td>
                                        <label class='checkbox-container'>
                                            <input type='checkbox' name='selected_data[]' value='" . htmlspecialchars($row["usn"]) . "|" . htmlspecialchars($row["name"]) . "' class='checkbox'>
                                            <span class='checkmark'></span>
                                        </label>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Error Message -->
            <div id="errorMessage" class="error-message">Please select at least one record before submitting.</div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>
</body>
</html>
