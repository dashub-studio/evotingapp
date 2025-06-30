
<?php
//session_start(); 
//include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $badgeId = $_POST['badge_id'];
    echo "11. '$badgeId'". "<br>";
    $email = $_POST['email'];
    echo "22. '$email'" . "<br>";
    $firstName = $_POST['first_name'];
    echo "33. '$firstName'" . "<br>";
    $lastName = $_POST['last_name'];
    echo "44. '$lastName'" . "<br>";

    //1. Database Connection
    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "voting_system";

    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    echo "55. Getting connection" . "<br>";
     // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
     echo "Connected successfully!!!" . "<br>";
     
    //2. Check if badge ID exists
    $sql = "SELECT * FROM register_user WHERE badge_id = '$badgeId'";
    echo "66. '$sql'" . "<br>";

    $result = $conn->query($sql);

    //3. Verify result
    if ($result === false) {
     // Query failed
     die("77. Query failed: " . $conn->error);
     // contact admin
     } 
     elseif ($result->num_rows > 0) {
     // Query successful and rows found
     echo "88. Rows found: " . $result->num_rows . "<br>";
 
     //4. Fetch and display data
     while ($row = $result->fetch_assoc()) {
         echo "Badge ID: " . $row['badge_id'] . "<br>";
         echo "First Name: " . $row['f_name'] . "<br>";
         echo "Last Name: " . $row['l_name'] . "<br>";
         echo "Email: " . $row['email'] . "<br>";
         echo "Is Registered: " . $row['is_registered'] . "<br>";
         echo "Registry Date: " . $row['Registry_Date'] . "<br>";
         echo "Voter ID: " . $row['voter_id'] . "<br>";
     }
     } else {
     // Query successful but no rows found
     echo "77. No results found for the given Badge ID. '$badgeId'" . "<br>";
     }
    
     //5. If badge_id found and voter_id not found then redirect to register 2.
     if ($result->num_rows == 0) {
          echo "Please Contact Admin." . "<br>";
      } 
      else {
          $row = $result->fetch_assoc();
          echo "88 . " . "<br>";
          if ($row['is_registered'] == 1 && $row['voter_id'] != null) {
              // Redirect to registration2 page with message
              echo "99 . You are already registered" . "<br>";
              header("Location: register2.php?status=already_registered&voter_id={$row['voter_id']}&badge_id=$badgeId");
          } else {
              // Redirect to registration2 page for registration
              echo "101 . You are not registered. Please register using registration page" . "<br>";
              header("Location: register2.php?status=not_registered&badge_id=$badgeId");
          }
      }

     
    
}
?>