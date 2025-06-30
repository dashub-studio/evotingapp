<?php
session_start();
include "includes/db_connect.php";

if (!isset($_GET['id'])) {
    die("❌ User ID is missing.");
}

$user_id = intval($_GET['id']);
$msg = "";

// Fetch user details
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM register_user WHERE id = $user_id"));

if (isset($_POST['update_user'])) {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $badge_id = $_POST['badge_id'];
    $voted = $_POST['voted']; // 'Y' or 'N'

    // If voted is set to 'N', clear the voter_id
    if ($voted === 'N') {
        $stmt = $conn->prepare("UPDATE register_user SET f_name = ?, l_name = ?, email = ?, badge_id = ?, voted = ?, voter_id = NULL WHERE id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE register_user SET f_name = ?, l_name = ?, email = ?, badge_id = ?, voted = ? WHERE id = ?");
    }

    if ($stmt) {
        if ($voted === 'N') {
            $stmt->bind_param("sssssi", $f_name, $l_name, $email, $badge_id, $voted, $user_id);
        } else {
            $stmt->bind_param("sssssi", $f_name, $l_name, $email, $badge_id, $voted, $user_id);
        }

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ User updated successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Update failed: " . $stmt->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>❌ SQL Prepare failed: " . $conn->error . "</div>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Edit User</h4>
        </div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="f_name" value="<?= $user['f_name'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="l_name" value="<?= $user['l_name'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Badge ID</label>
                    <input type="text" name="badge_id" value="<?= $user['badge_id'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Voted Status</label>
                    <select name="voted" class="form-select">
                        <option value="Y" <?= $user['voted'] == 'Y' ? 'selected' : '' ?>>Yes</option>
                        <option value="N" <?= $user['voted'] == 'N' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn btn-success">Update User</button>
                <a href="list_users.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
