<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM password_resets WHERE token = '$token' AND token_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Token is valid, show the password reset form
        if (isset($_POST['reset_password'])) {
            $new_password = $_POST['password'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

            // Get the email associated with the token
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];

            // Update the user's password in the users table
            $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
            if (mysqli_query($conn, $sql)) {
                // After password update, delete the token from the password_resets table
                $sql = "DELETE FROM password_resets WHERE token = '$token'";
                mysqli_query($conn, $sql);
                echo "Your password has been reset successfully.";
            } else {
                echo "Error resetting password.";
            }
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!-- Password Reset Form -->
<form method="POST">
    <label for="password">Enter New Password:</label>
    <input type="password" name="password" required>
    <button type="submit" name="reset_password">Reset Password</button>
</form>
