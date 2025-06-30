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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_name = trim($_POST['role_name']);

    if (!empty($role_name)) {
        $stmt = $conn->prepare("INSERT INTO role (role_name) VALUES (?)");
        $stmt->bind_param("s", $role_name);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Role added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Failed to add role.</div>";
        }

        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ Role name is required.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>🧑‍💼 Add Role</h3>
        </div>
        <div class="card-body">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" name="role_name" id="role_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add Role</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
