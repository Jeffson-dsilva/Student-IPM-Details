<?php
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

// Fetch USN from the URL
$usn = isset($_GET['usn']) ? $conn->real_escape_string($_GET['usn']) : '';

// Query to get details for the given USN
$sql = "SELECT * FROM mooc_courses WHERE usn = '$usn'";
$result = $conn->query($sql);
$details = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOC Course Details</title>
    <link rel="stylesheet" href="hodmooc_details.css">
</head>
<body>
    <div class="form-container">
        <h1>MOOC Course Details</h1>
        <?php if ($details): ?>
            <div class="form-group">
                <label>Name:</label>
                <p><?php echo htmlspecialchars($details['name']); ?></p>
            </div>
            <div class="form-group">
                <label>USN:</label>
                <p><?php echo htmlspecialchars($details['usn']); ?></p>
            </div>
            <div class="form-group">
                <label>Start Date:</label>
                <p><?php echo htmlspecialchars($details['start_date']); ?></p>
            </div>
            <div class="form-group">
                <label>End Date:</label>
                <p><?php echo htmlspecialchars($details['end_date']); ?></p>
            </div>
            <div class="form-group">
                <label>Certificate:</label><br>
                <?php if (!empty($details['certificate'])): ?>
                    <button id="viewCertificateBtn" class="btn">View Certificate</button>
                <?php else: ?>
                    <p>No certificate uploaded</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>No details found for the selected USN.</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="certificateModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <?php if (!empty($details['certificate'])): ?>
                <?php
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileType = finfo_buffer($fileInfo, $details['certificate']);
                finfo_close($fileInfo);

                if (strpos($fileType, 'image/') === 0): ?>
                    <img src="data:<?php echo $fileType; ?>;base64,<?php echo base64_encode($details['certificate']); ?>" class="uploaded-file-img">
                <?php elseif ($fileType === 'application/pdf'): ?>
                    <iframe src="data:application/pdf;base64,<?php echo base64_encode($details['certificate']); ?>" class="uploaded-file-iframe"></iframe>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('certificateModal');
        const btn = document.getElementById('viewCertificateBtn');
        const close = document.getElementById('closeModal');

        btn?.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        close?.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
