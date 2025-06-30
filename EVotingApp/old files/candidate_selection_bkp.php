<?php
session_start();
var_dump($_SESSION);
"<br>";

$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
var_dump($_POST);
"<br>";

$election_id = 1; // Dynamically set election ID
echo "01 election_id . '$election_id'";

// Fetch roles associated with the election
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

<h2>Select Your Candidate</h2>
<form method="POST" action="poll.php" id="voteForm">
    <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
    <input type="hidden" name="voter_id" value="<?php echo $_SESSION['voter_id']; ?>">
    <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
    <input type="hidden" name="badge_id" value="<?php echo $_SESSION['badge_id']; ?>">



    <?php
    while ($roleRow = mysqli_fetch_assoc($roleResult)) {
        $role_id = $roleRow['role_id'];
        $roleIds[] = $role_id;
        echo "<h3>" . $roleRow['role_name'] . "</h3>";

        // Fetch candidates for the role
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
            if (strpos($candidateRow['f_name'], 'NOTA') === false) {
                echo "<input type='radio' name='candidate_".$role_id."' value='".$candidateRow['candidate_id']."' onclick='validateSelection()' required> 
                      ".$candidateRow['f_name']." ".$candidateRow['l_name']."<br>";
            } else {
                $notaCandidateId = $candidateRow['candidate_id'];
            }
        }

        // Add NOTA option if it exists
        if ($notaCandidateId !== null) {
            echo "<input type='radio' name='candidate_".$role_id."' value='".$notaCandidateId."' onclick='validateSelection()' required> 
                  None of the Above (NOTA) <br>";
        }
    }
    ?>

    <button type="submit" id="submitBtn" disabled>Submit Vote</button>
</form>

<script>
    function validateSelection() {
        let allSelected = true;
        <?php foreach ($roleIds as $role_id) { ?>
            if (!document.querySelector('input[name="candidate_<?php echo $role_id; ?>"]:checked')) {
                allSelected = false;
            }
        <?php } ?>
        document.getElementById('submitBtn').disabled = !allSelected;
    }
</script>
