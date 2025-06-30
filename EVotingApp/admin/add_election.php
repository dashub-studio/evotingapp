<?php
session_start();

include "includes/db_connect.php";

$msg = "";

if (isset($_POST['add_election'])) {
    $name = $_POST['election_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $count = $_POST['count_date'];
    $tenure_start = $_POST['tenure_start'];
    $tenure_end = $_POST['tenure_end'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO election (name, start_date, end_date, count_date, tenure_start_date, tenure_end_date, description)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $start, $end, $count, $tenure_start, $tenure_end, $desc);

    if ($stmt->execute()) {
        echo "<script>alert('Election added successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css">
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
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Count Date</label>
                        <input type="date" name="count_date" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tenure Start Date</label>
                        <input type="date" name="tenure_start" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tenure End Date</label>
                        <input type="date" name="tenure_end" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" name="add_election" class="btn btn-success">Create Election</button>
                    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>