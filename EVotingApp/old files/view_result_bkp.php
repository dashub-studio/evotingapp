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

$resultQuery = "SELECT c.f_name, c.l_name, r.total_votes, r.is_winner 
                FROM results r
                JOIN candidate c ON r.candidate_id = c.candidate_id
                WHERE r.election_id = '$election_id'
                ORDER BY r.total_votes DESC";

$result = mysqli_query($conn, $resultQuery);
if (!$result) {
    die("Error fetching results: " . mysqli_error($conn));
}

echo "<h2>Election Results</h2><table border='1'>
      <tr><th>Candidate</th><th>Total Votes</th><th>Winner</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['f_name']} {$row['l_name']}</td>
            <td>{$row['total_votes']}</td>
            <td>" . ($row['is_winner'] ? "Yes 🎉" : "No") . "</td>
          </tr>";
}

echo "</table>";
?>
