<?php
session_start();
include "includes/db_connect.php";

$msg = "";

if (isset($_POST['add_user'])) {
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $email = trim($_POST['email']);
    $badge_id = trim($_POST['badge_id']);
    $voter_id = trim($_POST['voter_id']);

    if ($f_name && $l_name && $email && $badge_id && $voter_id) {
        $stmt = $conn->prepare("INSERT INTO register_user (f_name, l_name, email, badge_id, is_registered, registry_date, voter_id, voted) VALUES (?, ?, ?, ?, 'Y', CURDATE(), ?, 'N')");
        $stmt->bind_param("sssss", $f_name, $l_name, $email, $badge_id, $voter_id);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ User added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ All fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center"><h4>➕ Add User</h4></div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST">
                <div class="mb-3"><label>First Name</label><input type="text" name="f_name" class="form-control" required></div>
                <div class="mb-3"><label>Last Name</label><input type="text" name="l_name" class="form-control" required></div>
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label>Badge ID</label><input type="text" name="badge_id" class="form-control" required></div>
                <div class="mb-3"><label>Voter ID</label><input type="text" name="voter_id" class="form-control" required></div>
                <button type="submit" name="add_user" class="btn btn-success">Add User</button>
                <a href="dashboard.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
