<?php
$password = 'password123'; // Replace with your desired password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed password: " . $hashed_password;

$password = 'password123'; // The actual password
$hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

// Insert user into the database
$sql = "INSERT INTO users (username, password, role) VALUES ('student1', '$hashed_password', 'student')";
mysqli_query($conn, $sql);

$password = 'teacherpassword'; // The actual password
$hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

// Insert user into the database
$sql = "INSERT INTO users (username, password, role) VALUES ('teacher1', '$hashed_password', 'teacher')";
mysqli_query($conn, $sql);

?>
