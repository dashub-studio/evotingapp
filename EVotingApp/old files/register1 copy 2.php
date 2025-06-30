
<?php
//session_start(); 
//include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     // Get form data
     $badgeId = $_POST['badge_id'] ?? '';
     echo "11. '$badgeId'". "<br>";
     $email = $_POST['email'] ?? '';
     echo "22. '$email'" . "<br>";
     $firstName = $_POST['first_name'] ?? '';
     echo "33. '$firstName'" . "<br>";
     $lastName = $_POST['last_name'] ?? '';
     echo "44. '$lastName'" . "<br>";

    //1 Database connection
    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "voting_system";
    
    $conn = new mysqli($sname, $uname, $password, $db_name);
    echo "55. Getting connection" . "<br>";
    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully!!!" . "<br>";
    
    //2 Check if badge_id exists in the database
    $sql = "SELECT * FROM register_user WHERE badge_id = '$badgeId'";
    echo "66. '$sql'" . "<br>";
    $result = $conn->query($sql);

    //3. Verify result
    if ($result === false) {
        // Query failed
        die("Error: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        // Fetch user details
        $user = $result->fetch_assoc();
        echo "77. '$user'" . "<br>";
    
        // Check if user is already registered
        //if ($user['is_registered'] == 1 && !empty($user['voter_id'])){} 

        if (!empty($user['voter_id'])) {
            // Redirect to register2.php with user details
            $params = http_build_query($user);
            echo "88. '$params'" . "<br>";
            echo "88a. '$user['voter_id']'" . "<br>";
            header("Location: register2.php?$params");
            exit();
        } else {
            // User exists but is not registered yet
            //$params = http_build_query($user);
           // echo "881. '$params'" . "<br>";
            //header("Location: register2.php?$params");
           // exit();
           echo "<script>
            alert('You are not registered yet. Please proceed to registration.');
            window.history.back();
            </script>";
        }
    } else {
        // User does not exist in the database
        echo "<script>
            alert('Badge ID not found. Please contact the admin.');
            window.history.back();
        </script>";
    }
}   
 ?>