<?php
session_start();
include('db.php');

// Check if the user is a teacher
if ($_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM internship_posts";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Teacher Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="dashboard-title">Teacher Dashboard</h1>
        
        <div class="post-list">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="post-card">
                    <p><strong>Student ID:</strong> <?= htmlspecialchars($row['student_id']) ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>
                    <p><?= htmlspecialchars($row['content']) ?></p>
                    
                    <form method="POST" action="rate_post.php" class="rating-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <label for="rating">Rating (1-5):</label>
                        <input type="number" name="rating" min="1" max="5" required>
                        <textarea name="comment" placeholder="Leave a comment..." required></textarea>
                        <button type="submit" name="rate_post" class="btn">Submit Rating</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
