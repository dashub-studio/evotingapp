<?php

session_start(); // Start session here
// Retrieve user data from URL query parameters
$badgeId = $_GET['badge_id'] ?? '';
echo "11 badgeId. '$badgeId'";
$email = $_GET['email'] ?? '';
echo "12 email. '$email'";
$firstName = $_GET['f_name'] ?? '';
echo "13 firstName. '$firstName'";
$lastName = $_GET['l_name'] ?? '';
echo "14 lastName. '$lastName'";
$voterId = trim($_GET['VOTER_ID']) === 'NULL' ? null : $_GET['VOTER_ID'];
echo "15 voterId. '$voterId'";
$registrationDate = $_GET['REGISTRY_DATE'] ?? '';
echo "16 registrationDate. '$registrationDate'";
$isRegistered = $_GET['IS_REGISTERED'] ?? 0;
echo "17 isRegistered. '$isRegistered'";
$isRegisteredMessage = ($isRegistered == 1) ? "Yes" : "No";
echo "18 isRegisteredMessage. '$isRegisteredMessage'";

// Store them in session to use in next step
$_SESSION['register_data'] = [
    'badge_id' => $badgeId,
    'email' => $email,
    'first_name' => $firstName,
    'last_name' => $lastName,
    'voter_id' => $voterId,
    'registry_date' => $registrationDate,
    'is_registered' => $isRegistered
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registration Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4>📋 Registration Review</h4>
        </div>
        <div class="card-body">
          <form action="register2_submit.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Badge ID</label>
              <input type="text" class="form-control" name="badge_id" value="<?= htmlspecialchars($badgeId); ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($email); ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($firstName); ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($lastName); ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Voter ID</label>
              <input type="text" class="form-control" value="<?= ($voterId === null) ? 'Not Assigned' : htmlspecialchars($voterId); ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Registration Date</label>
              <input type="text" class="form-control" value="<?= !empty($registrationDate) ? htmlspecialchars($registrationDate) : 'Not Registered'; ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Is Registered?</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($isRegisteredMessage); ?>" readonly>
            </div>

            <div class="d-grid gap-2">
              <?php if ($isRegistered == 0 && $voterId === null): ?>
                <button type="submit" class="btn btn-success">Complete Registration</button>
              <?php else: ?>
                <button class="btn btn-secondary" disabled>You are already registered</button>
              <?php endif; ?>
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

</body>
</html>
