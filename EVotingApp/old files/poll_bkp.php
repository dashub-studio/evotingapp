<?php
//  ob_start();
session_start();
echo "Session ID: " . session_id() . "<br>";

$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

var_dump($_SESSION);
echo "<br>";
var_dump($_POST);
echo "<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_vote'])) {
    // Confirm vote action
    if (!isset($_SESSION['voter_id'])) {
        die("Session expired. Please log in again.");
    }
    $voter_id = $_SESSION['voter_id'];
    $election_id = trim($_POST['election_id']);
    echo "Voter ID: $voter_id<br>";
    echo "Election ID: '$election_id'<br>";

    // Check if the voter has already voted
    $checkVoterQuery = "SELECT voted FROM register_user WHERE voter_id = '$voter_id'";
    $voterResult = mysqli_query($conn, $checkVoterQuery);

    if (!$voterResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    $voterData = mysqli_fetch_assoc($voterResult);
    if ($voterData['voted'] == 'Y') {
        die("You have already voted. Redirecting to login page...");
    }
    // Insert votes for each selected candidate
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'candidate_') !== false) {
            $candidate_id = intval($value);
            //echo "Selected Election ID: '$election_id'<br>";
            //echo "Selected voter ID: '$voter_id'<br>";
            //echo "Selected Candidate ID: '$candidate_id'<br>";
            $insertVoteQuery = "INSERT INTO vote (voter_id, election_id, candidate_id) 
                    VALUES ('$voter_id', '$election_id', '$candidate_id')";
                  
            if (!mysqli_query($conn, $insertVoteQuery)) {
                die("❌ Error inserting vote for Candidate ID $candidate_id: " . mysqli_error($conn) . "<br>Query: $insertVoteQuery");
                //echo "❌ Vote NOT successfully recorded for Candidate ID: $candidate_id<br>";
            } else {
                echo "✅ Vote successfully recorded for Candidate ID: $candidate_id<br>";
            }
        }
    }
    // Mark voter as voted
    $updateVoterQuery = "UPDATE register_user SET voted = 'Y' WHERE voter_id = '$voter_id'";
    if (!mysqli_query($conn, $updateVoterQuery)) {
        die("Error updating voter status: " . mysqli_error($conn));
    } else {
        echo "Voter marked as voted.<br>";
    }

    header("Location: vote_confirmation.php?election_id=1");
    exit();
}

// Display confirmation form
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_vote'])) {
    echo "<h2>Review Your Vote</h2>";
    echo "<p>Please verify your candidate selections before confirming your vote:</p>";

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'candidate_') !== false) {
            // Fetch candidate name from the candidates table
            $candidate_id = intval($value);
            $query = "SELECT name FROM candidate WHERE candidate_id = $candidate_id";
            $result = mysqli_query($conn, $query);
    
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $candidate_name = $row['name'];
            } else {
                $candidate_name = "Unknown"; // Fallback in case of query failure
            }
    
            echo "<p><strong>Position " . substr($key, 9) . ":</strong> Candidate ID: $candidate_id, Name: $candidate_name</p>";
        }
    }
    
    // Confirmation form
    echo '<form method="POST" action="poll.php">';
    foreach ($_POST as $key => $value) {
        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
    }
    echo '<input type="hidden" name="confirm_vote" value="1">';
    echo '<button type="submit">Confirm Vote</button>';
    echo '</form>';

    // Option to go back to selection
    echo '<form method="POST" action="candidate_selection.php">';
    echo '<button type="submit">Go Back to Selection</button>';
    echo '</form>';
    exit();
}
?>

