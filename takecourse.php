<?php include('includes/db.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all courses from the database
$stmt = $conn->prepare("SELECT * FROM courses");
$stmt->execute();
$courses = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];

    // Check if the course already exists in user_courses
    $checkStmt = $conn->prepare("SELECT * FROM user_courses WHERE user_id = :user_id AND course_id = :course_id");
    $checkStmt->execute(['user_id' => $user_id, 'course_id' => $course_id]);

    if ($checkStmt->rowCount() === 0) {
        // Insert into user_courses
        $stmt = $conn->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)");
        $stmt->execute(['user_id' => $user_id, 'course_id' => $course_id]);
    }

    // Redirect to the corresponding course page
    header('Location: courses/course' . $course_id . '.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Take a Course</title>
</head>
<body>
<header>
    <h1>Select a Mathematics Course</h1>
</header>
<main>
    <form method="post" class="course-list">
        <!-- Dynamically Generated Courses -->
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <button type="submit" name="course_id" value="<?= $course['id'] ?>">Take This Course</button>
            </div>
        <?php endforeach; ?>

    </form>
</main>
</body>
</html>
