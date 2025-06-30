<?php
session_start();

// Database connection
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "voting_system";

$conn = new mysqli($sname, $uname, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = trim($_POST['voter_id']);
    $email = trim($_POST['email']);
    $badge_id = trim($_POST['badge_id']);

    $query = "SELECT voted FROM register_user WHERE voter_id = ? AND email = ? AND badge_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $voter_id, $email, $badge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        if ($row['voted'] === 'Y') {
            $error = "You have already voted. You cannot vote again.";
        } else {
            $_SESSION['voter_id'] = $voter_id;
            $_SESSION['email'] = $email;
            $_SESSION['badge_id'] = $badge_id;

            header("Location: candidate_selection.php");
            exit();
        }
    } else {
        $error = "Voter ID not found. Please register or contact admin.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Voter Login - Online Voting System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
          <h4>🔐 Voter Login</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <form method="POST" action="voter_login.php">
            <div class="mb-3">
              <label for="voter_id" class="form-label">Voter ID</label>
              <input type="text" class="form-control" id="voter_id" name="voter_id" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
              <label for="badge_id" class="form-label">Badge ID</label>
              <input type="text" class="form-control" id="badge_id" name="badge_id" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          Don't have an account? <a href="register.html">Register here</a><br>
          <a href="index.html" class="text-decoration-none">🏠 Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
