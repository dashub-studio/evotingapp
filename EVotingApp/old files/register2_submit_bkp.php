
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $badgeId = $_POST['badge_id'];
    echo "10. '$badgeId'";

    //1. Database Connection
    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "voting_system";

    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    echo "11. Getting connection";
     // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
     echo "12. Connected successfully!!!";

     if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Generate unique voter ID
    $voterId = uniqid("VOTER_");
    echo "15. '$voterId'";

    // Update user registration details
    $sql = "UPDATE register_user 
            SET is_registered = 1, 
                registry_date = NOW(), 
                voter_id = '$voterId' 
            WHERE badge_id = '$badgeId'";
    echo "16. '$sql'";

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();

     
}
?>
