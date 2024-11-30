<?php
session_start();
include('db.php'); // Include the database connection

// Check if the user is a student
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit();
}

// Initialize a message variable for success/error messages
$message = "";

// Handle form submission for creating a new post
if (isset($_POST['submit_post'])) {
    $student_id = $_SESSION['user_id'];
    $date = $_POST['date'] ?? ''; // Use null coalescing to prevent warnings
    $content = $_POST['content'] ?? '';

    if (!empty($date) && !empty($content)) {
        // Insert the new post into the database
        $sql = "INSERT INTO internship_posts (student_id, date, content) VALUES ('$student_id', '$date', '$content')";
        if (mysqli_query($conn, $sql)) {
            $message = "Post created successfully!";
        } else {
            $message = "Error creating post.";
        }
    } else {
        $message = "Date and content are required!";
    }
}

// Handle editing an existing post
if (isset($_POST['edit_post_submit'])) {
    $post_id = $_POST['post_id'];
    $date = $_POST['date'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($date) && !empty($content)) {
        // Update the post in the database
        $sql = "UPDATE internship_posts SET date = '$date', content = '$content' WHERE id = '$post_id' AND student_id = '" . $_SESSION['user_id'] . "'";
        if (mysqli_query($conn, $sql)) {
            $message = "Post updated successfully!";
        } else {
            $message = "Error updating post.";
        }
    } else {
        $message = "Date and content cannot be empty!";
    }
}

// Handle deleting a post
if (isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'] ?? 0;

    // Delete the post from the database
    $sql = "DELETE FROM internship_posts WHERE id = '$post_id' AND student_id = '" . $_SESSION['user_id'] . "'";
    if (mysqli_query($conn, $sql)) {
        $message = "Post deleted successfully!";
    } else {
        $message = "Error deleting post.";
    }
}

// Get the student's previous posts to display them
$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM internship_posts WHERE student_id = '$student_id' ORDER BY date DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Student Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="dashboard-title">Welcome to Your Dashboard</h1>
        
        <!-- Display success/error message -->
        <?php if (!empty($message)): ?>
            <div class="message-box">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Form for adding or editing posts -->
        <div class="post-form">
            <h2><?= isset($_POST['edit_post']) ? 'Edit Post' : 'Create a New Post' ?></h2>
            <form method="POST">
                <input type="hidden" name="post_id" value="<?= $_POST['post_id'] ?? '' ?>">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?= $_POST['date'] ?? '' ?>" required>
                
                <label for="content">Content</label>
                <textarea name="content" id="content" placeholder="Write about your day..." required><?= $_POST['content'] ?? '' ?></textarea>
                
                <button type="submit" name="<?= isset($_POST['edit_post']) ? 'edit_post_submit' : 'submit_post' ?>" class="btn">
                    <?= isset($_POST['edit_post']) ? 'Update Post' : 'Save Post' ?>
                </button>
            </form>
        </div>
        
        <!-- Display existing posts -->
        <div class="post-list">
            <h2>Your Posts</h2>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="post-card">
                    <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    
                    <div class="post-actions">
                        <!-- Edit Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="date" value="<?= htmlspecialchars($row['date']) ?>">
                            <input type="hidden" name="content" value="<?= htmlspecialchars($row['content']) ?>">
                            <button type="submit" name="edit_post" class="btn btn-edit">Edit</button>
                        </form>
                        
                        <!-- Delete Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_post" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
