<?php
session_start();
include "includes/db_connect.php";

$msg = "";

// Fetch elections for dropdown FIRST
$elections = mysqli_query($conn, "SELECT election_id, name FROM election");

if (isset($_POST['add_role'])) {
    $election_id = intval($_POST['election_id']);
    $role_name = trim($_POST['role_name']);
    $description = trim($_POST['description']);

    if ($election_id && $role_name && $description) {
        $stmt = $conn->prepare("INSERT INTO role (election_id, role_name, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $election_id, $role_name, $description);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Role added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ All fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>➕ Add Role</h3>
        </div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="election_id" class="form-label">Election</label>
                    <select name="election_id" id="election_id" class="form-select" required>
                        <option value="">-- Select Election --</option>
                        <?php
                        if ($elections && mysqli_num_rows($elections) > 0) {
                            while ($e = mysqli_fetch_assoc($elections)) {
                                echo "<option value='{$e['election_id']}'>{$e['name']}</option>";
                            }
                        } else {
                            echo "<option disabled>No elections available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" name="role_name" id="role_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>
                <button type="submit" name="add_role" class="btn btn-success">Add Role</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
