<?php
session_start();
include "includes/db_connect.php";

$users = mysqli_query($conn, "SELECT * FROM register_user ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>🧑‍💼 Registered Users</h4>
        </div>
        <div class="card-body">
            <a href="add_user.php" class="btn btn-success mb-3">➕ Add New User</a>
            <table class="table table-bordered table-striped text-center">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Badge ID</th>
                        <th>Voter ID</th>
                        <th>Registered?</th>
                        <th>Voted?</th>
                        <th>Registry Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($users)) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['f_name'] . " " . $row['l_name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['badge_id'] ?></td>
                            <td><?= $row['voter_id'] ?></td>
                            <td><?= $row['is_registered'] == 'Y' ? '✅ Yes' : '❌ No' ?></td>
                            <td><?= $row['voted'] == 'Y' ? '🗳️ Yes' : '❌ No' ?></td>
                            <td><?= $row['registry_date'] ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($users) === 0): ?>
                        <tr><td colspan="9">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
