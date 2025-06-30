<?php
session_start();

// Database connection
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = trim($_POST['voter_id']);
    $email = trim($_POST['email']);
    $badge_id = trim($_POST['badge_id']);

    // Prepare statement to prevent SQL injection
    $query = "SELECT voted FROM register_user WHERE voter_id = ? AND email = ? AND badge_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $voter_id, $email, $badge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        if ($row['voted'] === 'Y') {
            $error = "You have already voted. You cannot vote again.";
        } else {
            $_SESSION['voter_id'] = $voter_id;
            $_SESSION['email'] = $email;
            $_SESSION['badge_id'] = $badge_id;

            header("Location: candidate_selection.php"); // Redirect to poll.php after login
            exit();
        }
    } else {
        $error = "Voter ID is not present. If you have not registered, please go to the register page. If you are registered, please contact the admin.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voter Login</title>
</head>
<body>
    <h2>Voter Login</h2>
    <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    
    <form method="POST" action="voter_login.php"> <!-- Explicitly setting action -->
        <label>Voter ID:</label>
        <input type="text" name="voter_id" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Badge ID:</label>
        <input type="text" name="badge_id" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
