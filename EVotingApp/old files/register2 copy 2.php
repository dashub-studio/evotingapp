<?php
// Get user data from URL parameters
$badgeId = $_GET['badge_id'] ?? '';
echo "11. '$badgeId'";
$email = $_GET['email'] ?? '';
echo "12. '$email'";
$firstName = $_GET['first_name'] ?? '';
echo "13. '$firstName'";
$lastName = $_GET['last_name'] ?? '';
echo "14. '$lastName'";
$voterId = $_GET['voter_id'] ?? 'Not Assigned';
echo "15. '$voterId'";
$registrationDate = $_GET['registry_date'] ?? 'Not Registered';
echo "16. '$registrationDate'";
$isRegistered = $_GET['is_registered'] ?? 0;
echo "17. '$isRegistered'";

$isRegisteredMessage = ($isRegistered == 1) ? "Yes" : "No";
echo "18. '$isRegisteredMessage'";

// Check if user is already registered
if ($isRegistered == 1) {
    echo "<script>
        alert('You are already registered. Your Voter ID is $voterId.');
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page 2</title>
</head>
<body>
    <h2>Registration Details</h2>
    <form>
        <label>Badge ID:</label>
        <input type="text" value="<?php echo $badgeId; ?>" readonly><br>

        <label>Email:</label>
        <input type="text" value="<?php echo $email; ?>" readonly><br>

        <label>First Name:</label>
        <input type="text" value="<?php echo $firstName; ?>" readonly><br>

        <label>Last Name:</label>
        <input type="text" value="<?php echo $lastName; ?>" readonly><br>

        <label>Voter ID:</label>
        <input type="text" value="<?php echo $voterId; ?>" readonly><br>

        <label>Registration Date:</label>
        <input type="text" value="<?php echo $registrationDate; ?>" readonly><br>

        <label>Is Registered:</label>
        <input type="text" value="<?php echo $isRegisteredMessage; ?>" readonly><br>
    </form>

    <?php if ($isRegistered == 0): ?>
        <button onclick="alert('Registration functionality not implemented yet.')">Register</button>
    <?php else: ?>
        <button disabled>You are already registered </button>
    <?php endif; ?>
</body>
</html>
