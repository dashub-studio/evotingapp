
<?php
$status = $_GET['status'];
echo "11. '$status'";
$badgeId = $_GET['badge_id'];
echo "22. '$badgeId'";

//1. Database Connection
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = mysqli_connect($sname, $uname, $password, $db_name);
echo "33. Getting connection";
 // Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 echo "44. Connected successfully!!!";

$sql = "SELECT * FROM register_user WHERE badge_id = '$badgeId'";
echo "45. '$sql'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($status == "already_registered") {
    echo "You are already registered. Your Voter ID is: {$user['voter_id']}.";
    $disabled = "disabled";
} else {
    $disabled = "";
}
?>

<form action="register2_submit.php" method="POST">
    <label>Badge ID:</label>    
    <input type="text" name="badge_id" value="<?= $user['badge_id'] ?>">
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?= $user['f_name'] ?>" readonly>
    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?= $user['l_name'] ?>" readonly>
    <label>Email:</label>
    <input type="email" name="email" value="<?= $user['email'] ?>" readonly>
    <button type="submit" <?= $disabled ?>>Register</button>
</form>