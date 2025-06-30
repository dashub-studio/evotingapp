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

// Check if election_id is set
if (!isset($_GET['election_id'])) {
    die("❌ Election ID is missing.");
}

$election_id = intval($_GET['election_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>🗳️ Election Results</h3>
        </div>
        <div class="card-body">

<?php
// Fetch roles involved in the election
$rolesQuery = "SELECT DISTINCT r.role_id, r.role_name 
               FROM result res 
               JOIN role r ON res.role_id = r.role_id 
               WHERE res.election_id = $election_id 
               ORDER BY r.role_id";

$rolesResult = mysqli_query($conn, $rolesQuery);
if (!$rolesResult) {
    echo "<div class='alert alert-danger'>❌ Error fetching roles: " . mysqli_error($conn) . "</div>";
    exit();
}

// Display results per role
while ($role = mysqli_fetch_assoc($rolesResult)) {
    $role_id = $role['role_id'];
    $role_name = $role['role_name'];

    echo "<h4 class='mt-4 text-primary'>Role: $role_name</h4>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead class='table-secondary'>
            <tr>
                <th>Candidate Name</th>
                <th>Total Votes</th>
                <th>Rank</th>
            </tr>
          </thead><tbody>";

    // Fetch candidates with ranking
    $resultQuery = "SELECT res.rank, c.name AS candidate_name, res.total_votes 
                    FROM result res 
                    JOIN candidate c ON res.candidate_id = c.candidate_id 
                    WHERE res.election_id = $election_id AND res.role_id = $role_id 
                    ORDER BY res.rank";

    $resultData = mysqli_query($conn, $resultQuery);
    if (!$resultData) {
        echo "<tr><td colspan='3'>❌ Error fetching results: " . mysqli_error($conn) . "</td></tr>";
    } else {
        while ($row = mysqli_fetch_assoc($resultData)) {
            echo "<tr>
                    <td>{$row['candidate_name']}</td>
                    <td>{$row['total_votes']}</td>
                    <td>{$row['rank']}</td>
                  </tr>";
        }
    }

    echo "</tbody></table>";
}

mysqli_close($conn);
?>

        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-primary">Back to Home</a>
        </div>

        </div>
    </div>
</div>

</body>
</html>
