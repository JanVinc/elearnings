<?php
include('includes/db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['delete'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];

    // Delete the course from the user_courses table
    $stmt = $conn->prepare("DELETE FROM user_courses WHERE user_id = :user_id AND course_id = :course_id");
    $stmt->execute(['user_id' => $user_id, 'course_id' => $course_id]);

    // Optionally, redirect back to the dashboard
    header('Location: dashboard.php');
    exit();
} else {
    // If no course_id is provided, redirect to dashboard
    header('Location: dashboard.php');
    exit();
}
?>
