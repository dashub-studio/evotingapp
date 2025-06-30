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

$election_id = 1;

// Fetch roles
$roleQuery = "SELECT DISTINCT r.role_id, r.role_name 
              FROM role r 
              JOIN candidate c ON r.role_id = c.role_id 
              WHERE c.election_id = '$election_id'";

$roleResult = mysqli_query($conn, $roleQuery);
if (!$roleResult) {
    die("Error fetching roles: " . mysqli_error($conn));
}

$roleIds = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Select Candidate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4>🗳️ Select Your Candidate</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="poll.php" id="voteForm">
            <input type="hidden" name="election_id" value="<?= $election_id; ?>">
            <input type="hidden" name="voter_id" value="<?= $_SESSION['voter_id']; ?>">
            <input type="hidden" name="email" value="<?= $_SESSION['email']; ?>">
            <input type="hidden" name="badge_id" value="<?= $_SESSION['badge_id']; ?>">

            <?php
            while ($roleRow = mysqli_fetch_assoc($roleResult)) {
                $role_id = $roleRow['role_id'];
                $roleIds[] = $role_id;
                echo "<div class='mb-4'><h5 class='text-primary'>" . $roleRow['role_name'] . "</h5>";

                $candidateQuery = "SELECT c.candidate_id, u.f_name, u.l_name 
                                   FROM candidate c 
                                   JOIN register_user u ON c.user_id = u.id 
                                   WHERE c.election_id = '$election_id' AND c.role_id = '$role_id'";
                $candidateResult = mysqli_query($conn, $candidateQuery);

                if (!$candidateResult) {
                    die("Error fetching candidates: " . mysqli_error($conn));
                }

                $notaCandidateId = null;

                while ($candidateRow = mysqli_fetch_assoc($candidateResult)) {
                    if (stripos($candidateRow['f_name'], 'NOTA') === false) {
                        echo "<div class='form-check'>
                                <input class='form-check-input' type='radio' 
                                       name='candidate_$role_id' value='{$candidateRow['candidate_id']}' onclick='validateSelection()' required>
                                <label class='form-check-label'>
                                  {$candidateRow['f_name']} {$candidateRow['l_name']}
                                </label>
                              </div>";
                    } else {
                        $notaCandidateId = $candidateRow['candidate_id'];
                    }
                }

                if ($notaCandidateId !== null) {
                    echo "<div class='form-check'>
                            <input class='form-check-input' type='radio' 
                                   name='candidate_$role_id' value='$notaCandidateId' onclick='validateSelection()' required>
                            <label class='form-check-label'>
                              None of the Above (NOTA)
                            </label>
                          </div>";
                }

                echo "</div>"; // End role section
            }
            ?>

            <div class="d-grid">
              <button type="submit" id="submitBtn" class="btn btn-success" disabled>Submit Vote</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <a href="index.html" class="text-decoration-none">🏠 Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function validateSelection() {
      let allSelected = true;
      <?php foreach ($roleIds as $role_id): ?>
      if (!document.querySelector('input[name="candidate_<?= $role_id ?>"]:checked')) {
          allSelected = false;
      }
      <?php endforeach; ?>
      document.getElementById('submitBtn').disabled = !allSelected;
  }
</script>

</body>
</html>
