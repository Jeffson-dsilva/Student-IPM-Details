
    <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_ipm_system";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, usn FROM internship";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Table</title>
    <link rel="stylesheet" href="hodviewinternship.css">
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
    <div class="container">
        <h1>Internship Details</h1>
        <table>
            <thead>
                <tr>
                    <th>USN</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["usn"]) . "</td>
                                <td>
                                    <form action='hodview_details.php' method='GET'>
                                        <input type='hidden' name='usn' value='" . htmlspecialchars($row["usn"]) . "'>
                                        <button type='submit' class='name-button'>" . htmlspecialchars($row["name"]) . "</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
