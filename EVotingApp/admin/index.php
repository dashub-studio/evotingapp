<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>🛠️ Admin Dashboard</h3>
        </div>
        <div class="card-body text-center">
            <a href="add_election.php" class="btn btn-success m-2">Add Election</a>
            <a href="list_elections.php" class="btn btn-info m-2">Edit Election</a>
            <a href="add_role.php" class="btn btn-success m-2">Add Role</a>
            <a href="list_roles.php" class="btn btn-info m-2">Edit Role</a>
            <a href="add_candidate.php" class="btn btn-success m-2">Add Candidate</a>
            <a href="list_candidates.php" class="btn btn-info m-2">Edit Candidate</a>
            <a href="add_user.php" class="btn btn-success m-2">Add User</a>
            <a href="list_users.php" class="btn btn-info m-2">Edit User</a>

            <a href="compute_results.php" class="btn btn-success m-2">View Results</a>
            
            <a href="logout.php" class="btn btn-danger m-2">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
