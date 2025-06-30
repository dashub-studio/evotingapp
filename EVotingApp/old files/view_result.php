<?php
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$election_id = $_GET['election_id'] ?? 1;

// Fetch winner and runner-up per role
$topCandidatesQuery = "
    SELECT r.role_id, rl.role_name, c.f_name, c.l_name, r.total_votes, 
           DENSE_RANK() OVER (PARTITION BY r.role_id ORDER BY r.total_votes DESC) AS rank
    FROM results r
    JOIN candidate c ON r.candidate_id = c.candidate_id
    JOIN role rl ON r.role_id = rl.role_id
    WHERE r.election_id = '$election_id'
    ORDER BY r.role_id, rank ASC
";

$topResult = mysqli_query($conn, $topCandidatesQuery);
if (!$topResult) {
    die("Error fetching top candidates: " . mysqli_error($conn));
}

// Group winners and runner-ups
$highlights = [];
while ($row = mysqli_fetch_assoc($topResult)) {
    $role = $row['role_name'];
    $rank = $row['rank'];

    if (!isset($highlights[$role])) {
        $highlights[$role] = ['winner' => null, 'runner_up' => null];
    }

    if ($rank == 1 && !$highlights[$role]['winner']) {
        $highlights[$role]['winner'] = "{$row['f_name']} {$row['l_name']} ({$row['total_votes']} votes)";
    } elseif ($rank == 2 && !$highlights[$role]['runner_up']) {
        $highlights[$role]['runner_up'] = "{$row['f_name']} {$row['l_name']} ({$row['total_votes']} votes)";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .highlight-banner {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            animation: flash 1s infinite alternate;
            border-radius: 10px;
        }

        @keyframes flash {
            from { background-color: #007BFF; }
            to { background-color: #0056b3; }
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }

        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .role-section {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="highlight-banner">
    <h3>🎉 Election Highlights</h3>
    <ul>
        <?php foreach ($highlights as $role => $candidates): ?>
            <li><strong><?= $role ?>:</strong> 
                Winner - <?= $candidates['winner'] ?? 'N/A' ?> | 
                Runner-up - <?= $candidates['runner_up'] ?? 'N/A' ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
// Fetch and display full results
$resultQuery = "
    SELECT r.role_id, rl.role_name, c.f_name, c.l_name, r.total_votes, r.is_winner
    FROM results r
    JOIN candidate c ON r.candidate_id = c.candidate_id
    JOIN role rl ON r.role_id = rl.role_id
    WHERE r.election_id = '$election_id'
    ORDER BY r.role_id, r.total_votes DESC
";

$result = mysqli_query($conn, $resultQuery);
if (!$result) {
    die("Error fetching results: " . mysqli_error($conn));
}

$currentRole = null;
while ($row = mysqli_fetch_assoc($result)) {
    $role = $row['role_name'];
    if ($role !== $currentRole) {
        if ($currentRole !== null) {
            echo "</table><br>";
        }
        echo "<div class='role-section'>";
        echo "<h2>Role: $role</h2>";
        echo "<table><tr><th>Candidate</th><th>Total Votes</th><th>Winner</th></tr>";
        $currentRole = $role;
    }

    echo "<tr>
            <td>{$row['f_name']} {$row['l_name']}</td>
            <td>{$row['total_votes']}</td>
            <td>" . ($row['is_winner'] ? "Yes 🎉" : "No") . "</td>
          </tr>";
}
if ($currentRole !== null) {
    echo "</table></div>";
}

mysqli_close($conn);
?>

</body>
</html>
