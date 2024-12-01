<?php include('includes/db.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_courses INNER JOIN courses ON user_courses.course_id = courses.id WHERE user_courses.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <h2>Dashboard</h2>
    <table>
        <tr>
            <th>Course</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= $course['completed'] ? 'Completed' : 'In Progress' ?></td>
            <td>
                <?php if ($course['completed']): ?>
                    <!-- If the course is completed, show Delete button -->
                    <form method="post" action="delete_course.php">
                        <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                    </form>
                <?php else: ?>
                    <!-- If the course is in progress, show Cancel and Take Test buttons -->
                    <form method="post" action="cancel_course.php" style="display:inline;">
                        <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                        <button type="submit" name="cancel" onclick="return confirm('Are you sure you want to cancel this course?')">Cancel</button>
                    </form>
                    <a href="tests/test<?= $course['course_id'] ?>.php">
                        <button type="button">Take Test</button>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="button-container">
    <a href="takecourse.php" class="cta-btn">Take a New Course</a>
</body>
</html>
