<?php
session_start();
include "includes/db_connect.php";

$msg = "";

// Fetch users
$users = mysqli_query($conn, "SELECT id, f_name FROM register_user");

// Fetch elections
$elections = mysqli_query($conn, "SELECT election_id, name FROM election");

// Fetch roles
$roles = mysqli_query($conn, "SELECT role_id, role_name FROM role");

// Insert candidate
if (isset($_POST['add_candidate'])) {
    $user_id = intval($_POST['user_id']);
    $role_id = intval($_POST['role_id']);
    $election_id = intval($_POST['election_id']);
    $party_name = trim($_POST['party_name']);

    // Get first name
    $userQuery = mysqli_query($conn, "SELECT f_name FROM register_user WHERE id = $user_id");
    $userData = mysqli_fetch_assoc($userQuery);
    $f_name = $userData['f_name'];

    $stmt = $conn->prepare("INSERT INTO candidate (user_id, role_id, election_id, name, party_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $role_id, $election_id, $f_name, $party_name);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Candidate added successfully.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Candidate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script>
        function updateCandidateName() {
            const select = document.getElementById("user_id");
            const selectedOption = select.options[select.selectedIndex];
            document.getElementById("candidate_name").value = selectedOption.text;
        }
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3>👤 Add Candidate</h3>
        </div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User</label>
                    <select name="user_id" id="user_id" class="form-select" required onchange="updateCandidateName()">
                        <option value="">-- Select User --</option>
                        <?php while ($u = mysqli_fetch_assoc($users)) {
                            echo "<option value='{$u['id']}'>{$u['f_name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="candidate_name" class="form-label">Candidate Name (auto-filled)</label>
                    <input type="text" id="candidate_name" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="election_id" class="form-label">Select Election</label>
                    <select name="election_id" id="election_id" class="form-select" required>
                        <option value="">-- Select Election --</option>
                        <?php while ($e = mysqli_fetch_assoc($elections)) {
                            echo "<option value='{$e['election_id']}'>{$e['name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Select Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        <?php while ($r = mysqli_fetch_assoc($roles)) {
                            echo "<option value='{$r['role_id']}'>{$r['role_name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="party_name" class="form-label">Party Name</label>
                    <input type="text" name="party_name" id="party_name" class="form-control" required>
                </div>

                <button type="submit" name="add_candidate" class="btn btn-success">Add Candidate</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
