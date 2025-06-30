<?php
// Database connection
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$badgeId = $_POST['badge_id'] ?? '';
echo "11. '$badgeId'";
$email = $_POST['email'] ?? '';
echo "12. '$email'";
$firstName = $_POST['first_name'] ?? '';
echo "13. '$firstName'";
$lastName = $_POST['last_name'] ?? '';
echo "14. '$lastName'";

// Check if badge_id exists in the database
$sql = "SELECT * FROM register_user WHERE badge_id = '$badgeId'";
echo "66. '$sql'" . "<br>";
$result = $conn->query($sql);
echo "66a. '$result->num_rows'" . "<br>";

if ($result === false) {
    die("Query Error: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Fetch user details
    $user = $result->fetch_assoc();

    // Redirect user to register2.php with details
    $params = http_build_query($user);
    echo "Debug: register2.php?$params";
    header("Location: register2.php?$params");
    exit();
} else {
    // If user does not exist
    echo "<script>
        alert('Badge ID not found. Please contact the admin.');
        window.history.back();
    </script>";
}
?>
