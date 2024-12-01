<?php include('../includes/db.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$course_id = 1; // Test for Introduction to Calculus
$user_id = $_SESSION['user_id'];

// Fetch questions
$stmt = $conn->prepare("SELECT * FROM questions WHERE course_id = :course_id");
$stmt->execute(['course_id' => $course_id]);
$questions = $stmt->fetchAll();

// Shuffle questions
shuffle($questions);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($questions);
    foreach ($questions as $index => $question) {
        if (isset($_POST['answer_' . $question['id']]) && $_POST['answer_' . $question['id']] == $question['correct_option']) {
            $score++;
        }
    }

    // Update user_courses with score and completion
    $stmt = $conn->prepare("UPDATE user_courses SET completed = 1, score = :score WHERE user_id = :user_id AND course_id = :course_id");
    $stmt->execute(['score' => $score, 'user_id' => $user_id, 'course_id' => $course_id]);

    header("Location: ../results.php?course_id=$course_id&score=$score&total=$total");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
    <title>Test: Introduction to Calculus</title>
</head>
<body>
    <h2>Introduction to Calculus Test</h2>
    <form method="post">
    <?php foreach ($questions as $index => $question): ?>
        <div>
            <h3>Q<?= $index + 1 ?>: <?= $question['question'] ?></h3>
            <?php $options = json_decode($question['options']); ?>
            <div class="options">
                <?php foreach ($options as $key => $option): ?>
                    <div>
                        <input type="radio" name="answer_<?= $question['id'] ?>" value="<?= $key ?>"> <?= $option ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="submit">Submit</button>
</form>
</body>
</html>
