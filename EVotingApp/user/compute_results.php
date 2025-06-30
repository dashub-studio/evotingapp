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

$election_id = isset($_GET['election_id']) ? intval($_GET['election_id']) : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .compact-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .winner {
            color: #ffd700; /* gold */
            font-weight: bold;
            text-shadow: 1px 1px 2px #000;
        }
        .runner-up {
            color: #fcd34d; /* soft golden yellow */
            font-weight: bold;
            text-shadow: 1px 1px 2px #000;
        }
        .header-highlight {
            background-color: #007bff; /* Blue header */
            color: white;
            padding: 20px;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        .role-section {
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            text-align: center;
        }
        .role-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .role-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap; /* Ensure the content wraps nicely on smaller screens */
        }
        .role-container .role-section {
            width: 30%; /* Adjust width to fit content */
        }
        .winner-container {
            margin-top: 15px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #007bff; /* Blue header */
            color: white; /* White text on blue background */
        }
        .results-section {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container compact-container mt-5">
    <div class="card shadow-sm">
        <div class="card-header header-highlight text-center">
            <h3>🗳️ Election Results</h3>

            <!-- Display winner and runner-up for each category in the header section -->
            <div class="role-container">
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

                // Display each role and its winner and runner-up in the header section
                while ($role = mysqli_fetch_assoc($rolesResult)) {
                    $role_id = $role['role_id'];
                    $role_name = $role['role_name'];

                    echo "<div class='role-section'>";
                    echo "<div class='role-name text-primary'>$role_name</div>";

                    // Fetch the winner and runner-up for this role
                    $resultQuery = "SELECT res.rank, c.name AS candidate_name, res.total_votes 
                                    FROM result res 
                                    JOIN candidate c ON res.candidate_id = c.candidate_id 
                                    WHERE res.election_id = $election_id AND res.role_id = $role_id 
                                    ORDER BY res.rank";

                    $resultData = mysqli_query($conn, $resultQuery);
                    if (!$resultData) {
                        echo "<div class='alert alert-danger'>❌ Error fetching results: " . mysqli_error($conn) . "</div>";
                        exit();
                    }

                    $count = 0;
                    $winner = $runner_up = "";

                    while ($row = mysqli_fetch_assoc($resultData)) {
                        if ($count == 0) {
                            $winner = $row['candidate_name'];
                        } elseif ($count == 1) {
                            $runner_up = $row['candidate_name'];
                        }
                        $count++;
                    }

                    // Display winner and runner-up in the header section
                    echo "<div class='winner-container'>";
                    echo "<div class='winner'>$winner - Winner 🎉</div>";
                    echo "<div class='runner-up'>$runner_up - Runner-up 🥈</div>";
                    echo "</div>"; // Close winner-container div

                    echo "</div>"; // Close role-section div
                }
                ?>

            </div>
        </div>

        <!-- Results Section -->
        <div class="card-body results-section">
            <h4 class="mt-4 text-center text-primary">Full Election Results</h4>

            <?php
            // Display results per role
            $rolesResult = mysqli_query($conn, $rolesQuery);
            if (!$rolesResult) {
                echo "<div class='alert alert-danger'>❌ Error fetching roles: " . mysqli_error($conn) . "</div>";
                exit();
            }

            // Display each role and its results in a table
            while ($role = mysqli_fetch_assoc($rolesResult)) {
                $role_id = $role['role_id'];
                $role_name = $role['role_name'];

                echo "<h5 class='mt-4 text-primary text-center'>$role_name</h5>";
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
