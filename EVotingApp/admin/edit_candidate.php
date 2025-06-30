<?php
session_start();
include "includes/db_connect.php";

$msg = "";

// Get candidate ID
if (!isset($_GET['candidate_id'])) {
    die("❌ Candidate ID is missing.");
}
$candidate_id = intval($_GET['candidate_id']);

// Fetch existing candidate data
$candidateQuery = mysqli_query($conn, "SELECT * FROM candidate WHERE candidate_id = $candidate_id");
if (mysqli_num_rows($candidateQuery) == 0) {
    die("❌ Candidate not found.");
}
$candidate = mysqli_fetch_assoc($candidateQuery);

// Fetch dropdown options
$users = mysqli_query($conn, "SELECT id, f_name FROM register_user");
$elections = mysqli_query($conn, "SELECT election_id, name FROM election");
$roles = mysqli_query($conn, "SELECT role_id, role_name FROM role");

// Handle form submission
if (isset($_POST['update_candidate'])) {
    $user_id = intval($_POST['user_id']);
    $role_id = intval($_POST['role_id']);
    $election_id = intval($_POST['election_id']);
    $party_name = trim($_POST['party_name']);

    // Get f_name
    $userQuery = mysqli_query($conn, "SELECT f_name FROM register_user WHERE id = $user_id");
    $userData = mysqli_fetch_assoc($userQuery);
    $f_name = $userData['f_name'];

    $stmt = $conn->prepare("UPDATE candidate SET user_id=?, role_id=?, election_id=?, name=?, party_name=? WHERE candidate_id=?");
    $stmt->bind_param("iiissi", $user_id, $role_id, $election_id, $f_name, $party_name, $candidate_id);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Candidate updated successfully.</div>";
        // Refresh candidate data
        $candidate = [
            'user_id' => $user_id,
            'role_id' => $role_id,
            'election_id' => $election_id,
            'party_name' => $party_name,
            'name' => $f_name
        ];
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Candidate</title>
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
            <h3>✏️ Edit Candidate</h3>
        </div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User</label>
                    <select name="user_id" id="user_id" class="form-select" required onchange="updateCandidateName()">
                        <option value="">-- Select User --</option>
                        <?php while ($u = mysqli_fetch_assoc($users)) {
                            $selected = $u['id'] == $candidate['user_id'] ? "selected" : "";
                            echo "<option value='{$u['id']}' $selected>{$u['f_name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="candidate_name" class="form-label">Candidate Name</label>
                    <input type="text" id="candidate_name" class="form-control" value="<?= htmlspecialchars($candidate['name']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="election_id" class="form-label">Select Election</label>
                    <select name="election_id" id="election_id" class="form-select" required>
                        <option value="">-- Select Election --</option>
                        <?php while ($e = mysqli_fetch_assoc($elections)) {
                            $selected = $e['election_id'] == $candidate['election_id'] ? "selected" : "";
                            echo "<option value='{$e['election_id']}' $selected>{$e['name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Select Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        <?php while ($r = mysqli_fetch_assoc($roles)) {
                            $selected = $r['role_id'] == $candidate['role_id'] ? "selected" : "";
                            echo "<option value='{$r['role_id']}' $selected>{$r['role_name']}</option>";
                        } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="party_name" class="form-label">Party Name</label>
                    <input type="text" name="party_name" id="party_name" class="form-control" required value="<?= htmlspecialchars($candidate['party_name']) ?>">
                </div>

                <button type="submit" name="update_candidate" class="btn btn-primary">Update Candidate</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
