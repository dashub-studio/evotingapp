<?php
session_start();
include "includes/db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h4>🧑‍💼 All Candidates</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Candidate Name</th>
                        <th>Party</th>
                        <th>Role</th>
                        <th>Election</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT c.candidate_id, c.name, c.party_name, r.role_name, e.name AS election_name 
                              FROM candidate c 
                              JOIN role r ON c.role_id = r.role_id 
                              JOIN election e ON c.election_id = e.election_id";

                    $result = mysqli_query($conn, $query);
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['party_name']}</td>
                                <td>{$row['role_name']}</td>
                                <td>{$row['election_name']}</td>
                                <td>
                                    <a href='edit_candidate.php?candidate_id={$row['candidate_id']}' class='btn btn-sm btn-info'>Edit</a>
                                </td>
                              </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
