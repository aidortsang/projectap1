<?php
session_start();
include('db.php'); // Include the database connection

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // If user exists and password is correct
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role (student or teacher)
        if ($user['role'] == 'student') {
            header('Location: student_dashboard.php');
        } else {
            header('Location: teacher_dashboard.php');
        }
        exit(); // End the script after redirection
    } else {
        echo "<p class='error-message'>Invalid username or password.</p>"; // Error message for incorrect login
    }
}
?>
