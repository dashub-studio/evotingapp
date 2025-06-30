<?php
session_start();

$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$msg = "";

// Fetch available roles and elections
$roles = mysqli_query($conn, "SELECT * FROM role ORDER BY role_name");
$elections = mysqli_query($conn, "SELECT * FROM election ORDER BY election_id DESC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $role_id = intval($_POST['role_id']);
    $election_id = intval($_POST['election_id']);

    if (!empty($name) && $role_id && $election_id) {
        $stmt = $conn->prepare("INSERT INTO candidate (name, role_id, election_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $name, $role_id, $election_id);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Candidate added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Failed to add candidate.</div>";
        }

        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ All fields are required.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Candidate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>👤 Add Candidate</h3>
        </div>
        <div class="card-body">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Candidate Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        <?php while ($r = mysqli_fetch_assoc($roles)) {
                            echo "<option value='{$r['role_id']}'>{$r['role_name']}</option>";
                        } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="election_id" class="form-label">Election</label>
                    <select name="election_id" id="election_id" class="form-select" required>
                        <option value="">-- Select Election --</option>
                        <?php while ($e = mysqli_fetch_assoc($elections)) {
                            echo "<option value='{$e['election_id']}'>{$e['election_name']}</option>";
                        } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Add Candidate</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
