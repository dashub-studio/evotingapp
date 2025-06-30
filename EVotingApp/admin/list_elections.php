<?php
session_start();
//if (!isset($_SESSION['admin'])) {
//    header("Location: login.php");
//    exit();
//}
include "includes/header.php";
include "includes/db_connect.php";
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3>🗳️ Manage Elections</h3>
        </div>
        <div class="card-body">

            <a href="add_election.php" class="btn btn-success mb-3">Add New Election</a>

            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Count Date</th>
                        <th>Tenure Start</th>
                        <th>Tenure End</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $query = "SELECT * FROM election ORDER BY election_id DESC";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "<tr><td colspan='9'>❌ Error fetching elections: " . mysqli_error($conn) . "</td></tr>";
                    } elseif (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='9'>No elections found.</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['election_id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['end_date']}</td>
                                    <td>{$row['count_date']}</td>
                                    <td>{$row['tenure_start_date']}</td>
                                    <td>{$row['tenure_end_date']}</td>
                                    <td>{$row['description']}</td>
                                    <td>
                                        <a href='edit_election.php?id={$row['election_id']}' class='btn btn-info btn-sm'>Edit</a>
                                    </td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>

        </div>
    </div>
</div>
