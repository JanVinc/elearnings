<?php
include('includes/db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['cancel'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];

    // You may want to update the course status to 'cancelled' or remove it from the user_courses table
    // Assuming a status column exists to track in-progress courses:
    // Example of marking as canceled (optional):
    // $stmt = $conn->prepare("UPDATE user_courses SET status = 'canceled' WHERE user_id = :user_id AND course_id = :course_id");

    // Remove the course from the user_courses table (if it's just removal without status tracking)
    $stmt = $conn->prepare("DELETE FROM user_courses WHERE user_id = :user_id AND course_id = :course_id");
    $stmt->execute(['user_id' => $user_id, 'course_id' => $course_id]);

    // Redirect back to the dashboard
    header('Location: dashboard.php');
    exit();
} else {
    // If no course_id is provided, redirect to dashboard
    header('Location: dashboard.php');
    exit();
}
?>
