<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $badgeId = $_POST['badge_id'] ?? '';
    $email = $_POST['email'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';

    // 1. Database Connection
    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "voting_system";

    $conn = new mysqli($sname, $uname, $password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Verify user exists
    $checkSql = "SELECT voter_id FROM register_user WHERE badge_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $badgeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $message = "❌ No user found with Badge ID: <strong>$badgeId</strong>";
    } else {
        $existingVoterId = $row['voter_id'];
        //echo "10 existingVoterId. '$existingVoterId'";

        if (is_null($existingVoterId) || trim($existingVoterId) === '' || strtoupper(trim($existingVoterId)) === 'NULL') {
            // Generate and assign new voter_id
            // 3. Generate voter ID & update record
            $voterId = uniqid("VOTER_");
            $updateSql = "UPDATE register_user 
                          SET is_registered = 1, 
                              registry_date = NOW(), 
                              voter_id = ? 
                          WHERE badge_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ss", $voterId, $badgeId);
            if ($updateStmt->execute()) {
                $message = "✅ Registration successful! Your Voter ID is: <strong>$voterId</strong>";
            } else {
                $message = "❌ Failed to update registration: " . $conn->error;
            }
            $updateStmt->close();
        } else {
            $message = "✅ You are already registered. Your Voter ID is: <strong>$existingVoterId</strong>";
        }
    }

    $conn->close();
} else {
    $message = "❌ Invalid access.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4>🎉 Registration Status</h4>
                </div>
                <div class="card-body text-center">
                    <p class="fs-5"><?= $message ?></p>
                    <a href="index.html" class="btn btn-primary mt-3">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
