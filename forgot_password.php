<?php
include('db.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a secure token for password reset
        $token = bin2hex(random_bytes(32)); // Generates a 64-character token

        // Set token expiry time (e.g., 1 hour from now)
        $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insert the token into the password_resets table
        $sql = "INSERT INTO password_resets (email, token, token_expiry) VALUES ('$email', '$token', '$expiry_time')";
        if (mysqli_query($conn, $sql)) {
            // Send the reset link to the user's email
            $resetLink = "http://localhost/project_aptstage/reset_password.php?token=$token";
            mail($email, "Reset Password", "Click here to reset your password: $resetLink");
            echo "Check your email for the reset link.";
        } else {
            echo "Error generating password reset token.";
        }
    } else {
        echo "This email is not registered.";
    }
}
?>

<!-- Forgot Password Form -->
<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit" name="submit">Send Reset Link</button>
</form>
