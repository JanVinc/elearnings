<?php
include('../includes/db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Course ID for Statistics and Probability
$course_id = 3; // Adjust as needed
$user_id = $_SESSION['user_id'];

// Fetch course details from the database
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = :id");
$stmt->execute(['id' => $course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found. Please contact support or select a different course.");
}

// Optionally, fetch enrolled students
$students_stmt = $conn->prepare("SELECT users.username FROM users 
                                 JOIN enrollments ON enrollments.user_id = users.id 
                                 WHERE enrollments.course_id = :course_id");
$students_stmt->execute(['course_id' => $course_id]);
$students = $students_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title><?= htmlspecialchars($course['title']) ?></title>
</head>
<body>
<header>
    <h1><?= htmlspecialchars($course['title']) ?></h1>
</header>

<main>
    <section class="course-details">
        <h2>Course Description</h2>
        <p><?= htmlspecialchars($course['description']) ?></p>
    </section>

    <section class="course-content">
        <h3>Course Content</h3>
        <p>Statistics and Probability deal with data collection, analysis, interpretation, and prediction. In this course, we will cover topics like:</p>
        <ul>
            <li>Data Representation</li>
            <li>Probability Basics</li>
            <li>Random Variables and Distributions</li>
            <li>Combinatorics</li>
        </ul>
    </section>

    <section class="test-section">
        <a href="../tests/test3.php" class="btn">Take the Test</a>
    </section>

    <section class="back-button">
        <a href="../takecourse.php" class="btn">Back to Courses</a>
    </section>
</main>

<footer>
    <p>&copy; <?= date("Y") ?> E-Learning Platform</p>
</footer>
</body>
</html>
