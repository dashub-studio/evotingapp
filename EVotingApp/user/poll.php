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

// Confirm vote submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_vote'])) {
    if (!isset($_SESSION['voter_id'])) {
        die("Session expired. Please log in again.");
    }

    $voter_id = $_SESSION['voter_id'];
    $election_id = trim($_POST['election_id']);

    // Check if already voted
    $checkVoterQuery = "SELECT voted FROM register_user WHERE voter_id = '$voter_id'";
    $voterResult = mysqli_query($conn, $checkVoterQuery);
    $voterData = mysqli_fetch_assoc($voterResult);

    if ($voterData['voted'] == 'Y') {
        die("You have already voted.");
    }

    // Insert votes
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'candidate_') !== false) {
            $candidate_id = intval($value);
            $insertVoteQuery = "INSERT INTO vote (voter_id, election_id, candidate_id) 
                                VALUES ('$voter_id', '$election_id', '$candidate_id')";
            if (!mysqli_query($conn, $insertVoteQuery)) {
                die("❌ Error inserting vote: " . mysqli_error($conn));
            }
        }
    }

    // Mark voter as voted
    $updateVoterQuery = "UPDATE register_user SET voted = 'Y' WHERE voter_id = '$voter_id'";
    mysqli_query($conn, $updateVoterQuery);

    header("Location: vote_confirmation.php?election_id=$election_id");
    exit();
}

// Display confirmation form
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Your Vote</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4>📝 Review Your Vote</h4>
        </div>
        <div class="card-body">
          <p class="mb-4">Please verify your candidate selections before confirming your vote:</p>
          
          <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_vote'])) {
              echo "<ul class='list-group mb-4'>";
              foreach ($_POST as $key => $value) {
                  if (strpos($key, 'candidate_') !== false) {
                      $candidate_id = intval($value);
                      $query = "SELECT name FROM candidate WHERE candidate_id = $candidate_id";
                      $result = mysqli_query($conn, $query);
                      $candidate_name = ($result && $row = mysqli_fetch_assoc($result)) ? $row['name'] : "Unknown";

                      $position = substr($key, 9);
                      echo "<li class='list-group-item'><strong>Position $position:</strong> $candidate_name (ID: $candidate_id)</li>";
                  }
              }
              echo "</ul>";
          ?>

          <form method="POST" action="poll.php" class="mb-3">
              <?php
              foreach ($_POST as $key => $value) {
                  echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
              }
              ?>
              <input type="hidden" name="confirm_vote" value="1">
              <div class="d-grid">
                <button type="submit" class="btn btn-success">✅ Confirm Vote</button>
              </div>
          </form>

          <form method="POST" action="candidate_selection.php">
              <?php
              foreach ($_POST as $key => $value) {
                  if (strpos($key, 'candidate_') !== false || in_array($key, ['election_id', 'voter_id'])) {
                      echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                  }
              }
              ?>
              <div class="d-grid">
                <button type="submit" class="btn btn-secondary">🔙 Go Back to Selection</button>
              </div>
          </form>

          <?php } else { ?>
              <div class="alert alert-danger">Invalid access method.</div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
