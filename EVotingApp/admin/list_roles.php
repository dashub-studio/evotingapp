<?php
session_start();
include "includes/db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>📋 List of Roles</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-info">
                    <tr>
                        <th>Role ID</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Election</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT r.*, e.name AS election_name 
                              FROM role r 
                              JOIN election e ON r.election_id = e.election_id";
                    $result = mysqli_query($conn, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['role_id']}</td>
                                    <td>{$row['role_name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['election_name']}</td>
                                    <td>
                                        <a href='edit_role.php?role_id={$row['role_id']}' class='btn btn-sm btn-info'>Edit</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No roles found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
</body>
</html>
