<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$usn = isset($_GET['usn']) ? $conn->real_escape_string($_GET['usn']) : '';

$sql = "SELECT * FROM internship WHERE usn = '$usn'";
$result = $conn->query($sql);
$details = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Details</title>
    <link rel="stylesheet" href="hodview_details.css">
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
    <div class="form-container">
        <h1>Internship Details</h1>
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
                <label>Role:</label>
                <p><?php echo htmlspecialchars($details['role']); ?></p>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <p><?php echo htmlspecialchars($details['phone']); ?></p>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <p><?php echo htmlspecialchars($details['location']); ?></p>
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
                <label>Languages Used:</label>
                <p><?php echo htmlspecialchars($details['languages_used']); ?></p>
            </div>
            <div class="form-group">
                <label>Certificate:</label><br><br>
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
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileType = finfo_buffer($finfo, $details['certificate']);
                finfo_close($finfo);

                if (strpos($fileType, 'image') !== false): ?>
                    <img src="data:<?php echo $fileType; ?>;base64,<?php echo base64_encode($details['certificate']); ?>" alt="Internship Certificate" class="certificate-image">
                <?php elseif ($fileType === 'application/pdf'): ?>
                    <embed src="data:application/pdf;base64,<?php echo base64_encode($details['certificate']); ?>" type="application/pdf" width="100%" height="600px">
                <?php else: ?>
                    <p>Unsupported certificate format.</p>
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
