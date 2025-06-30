<?php
session_start();
include "includes/header.php";
include "includes/db_connect.php";

// Check if election_id is provided in the URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>❌ Election ID is missing.</div>";
    exit();
}

$election_id = intval($_GET['id']);

// Handle form submission
if (isset($_POST['update_election'])) {
    $name = $_POST['name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $count = $_POST['count_date'];
    $tenure_start = $_POST['tenure_start'];
    $tenure_end = $_POST['tenure_end'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("UPDATE election SET name=?, start_date=?, end_date=?, count_date=?, tenure_start_date=?, tenure_end_date=?, description=? WHERE election_id=?");
    $stmt->bind_param("sssssssi", $name, $start, $end, $count, $tenure_start, $tenure_end, $desc, $election_id);

    if ($stmt->execute()) {
        echo "<script>alert('Election updated successfully');</script>";
    } else {
        echo "<div class='alert alert-danger'>❌ Error updating election: " . $stmt->error . "</div>";
    }
}

// Fetch current data
$query = "SELECT * FROM election WHERE election_id = $election_id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "<div class='alert alert-danger'>❌ Election not found.</div>";
    exit();
}

$data = mysqli_fetch_assoc($result);
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white text-center">
            <h3>✏️ Edit Election</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Election Name</label>
                    <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($data['name']) ?>">
                </div>
                <div class="mb-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" required value="<?= $data['start_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" required value="<?= $data['end_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>Count Date</label>
                    <input type="date" name="count_date" class="form-control" required value="<?= $data['count_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>Tenure Start Date</label>
                    <input type="date" name="tenure_start" class="form-control" required value="<?= $data['tenure_start_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>Tenure End Date</label>
                    <input type="date" name="tenure_end" class="form-control" required value="<?= $data['tenure_end_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($data['description']) ?></textarea>
                </div>
                <button type="submit" name="update_election" class="btn btn-info">Update Election</button>
                <a href="list_elections.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
