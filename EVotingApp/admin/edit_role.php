<?php
session_start();
include "includes/db_connect.php";

if (!isset($_GET['role_id'])) {
    die("❌ Role ID is missing.");
}

$role_id = intval($_GET['role_id']);
$msg = "";

// Fetch current role data
$roleResult = mysqli_query($conn, "SELECT * FROM role WHERE role_id = $role_id");
if (!$roleResult || mysqli_num_rows($roleResult) == 0) {
    die("❌ Role not found.");
}
$role = mysqli_fetch_assoc($roleResult);

// Fetch elections for dropdown
$elections = mysqli_query($conn, "SELECT election_id, name FROM election");

// Handle form submission
if (isset($_POST['update_role'])) {
    $election_id = intval($_POST['election_id']);
    $role_name = trim($_POST['role_name']);
    $description = trim($_POST['description']);

    if ($election_id && $role_name && $description) {
        $stmt = $conn->prepare("UPDATE role SET election_id = ?, role_name = ?, description = ? WHERE role_id = ?");
        $stmt->bind_param("issi", $election_id, $role_name, $description, $role_id);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Role updated successfully.</div>";
            // Refresh data
            $role['election_id'] = $election_id;
            $role['role_name'] = $role_name;
            $role['description'] = $description;
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
    <title>Edit Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white text-center">
            <h3>✏️ Edit Role</h3>
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
                                $selected = ($e['election_id'] == $role['election_id']) ? 'selected' : '';
                                echo "<option value='{$e['election_id']}' $selected>{$e['name']}</option>";
                            }
                        } else {
                            echo "<option disabled>No elections available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" name="role_name" id="role_name" class="form-control" required value="<?= htmlspecialchars($role['role_name']) ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($role['description']) ?></textarea>
                </div>
                <button type="submit" name="update_role" class="btn btn-info">Update Role</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
