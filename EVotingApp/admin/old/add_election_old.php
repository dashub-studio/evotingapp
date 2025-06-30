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
    $election_name = trim($_POST['election_name']);

    if (!empty($election_name)) {
        $stmt = $conn->prepare("INSERT INTO election (election_name) VALUES (?)");
        $stmt->bind_param("s", $election_name);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Election created successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Failed to create election.</div>";
        }

        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ Election name cannot be empty.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Election</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>🗳️ Create New Election</h3>
        </div>
        <div class="card-body">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="election_name" class="form-label">Election Name</label>
                    <input type="text" name="election_name" id="election_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Create Election</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
