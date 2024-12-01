<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'];
$score = $_GET['score'];
$total = $_GET['total'];

// Fetch course details
$stmt = $conn->prepare("SELECT title FROM courses WHERE id = :course_id");
$stmt->execute(['course_id' => $course_id]);
$course = $stmt->fetch();

$passed = $score >= ($total * 0.6); // Passing condition: 60% or higher
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Test Results</title>
</head>
<body>
<header>
    <h1>Test Results</h1>
</header>
<main class="card">
    <h2><?= htmlspecialchars($course['title']); ?></h2>
    <p><strong>Score:</strong> <?= $score ?>/<?= $total ?></p>
    <p><strong>Status:</strong> <?= $passed ? '<span style="color:green;">Passed</span>' : '<span style="color:red;">Failed</span>' ?></p>
    <p>Review your mistakes and correct answers below:</p>
    <a href="dashboard.php" class="btn">Proceed to Dashboard</a>
    <ul>
        <?php
        // Fetch all questions and user's answers
        $stmt = $conn->prepare("SELECT q.question, q.correct_option, q.options, ua.answer 
                                FROM questions q 
                                LEFT JOIN user_answers ua 
                                ON q.id = ua.question_id AND ua.user_id = :user_id 
                                WHERE q.course_id = :course_id");
        $stmt->execute(['user_id' => $user_id, 'course_id' => $course_id]);
        $results = $stmt->fetchAll();

        foreach ($results as $result) {
            $options = json_decode($result['options']);
            echo "<li><strong>Question:</strong> " . htmlspecialchars($result['question']) . "<br>";
            echo "<strong>Your Answer:</strong> " . htmlspecialchars($options[$result['answer']] ?? "None") . "<br>";
            echo "<strong>Correct Answer:</strong> " . htmlspecialchars($options[$result['correct_option']]) . "</li><br>";
        }
        ?>
    </ul>
</body>
</html>
