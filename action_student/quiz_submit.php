<?php


require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Question.php';

$quizId = $_SESSION['current_quiz'] ?? null;
$answers = $_SESSION['answers'] ?? [];

if (!$quizId || empty($answers)) {
    die('Quiz introuvable ou aucune réponse enregistrée.');
}

if (!isset($_SESSION['user_id'])) {
    die('Utilisateur non connecté. Impossible d’enregistrer le score.');
}

$questionObj = new Question();
$questions = $questionObj->getQuestionsByQuizId($quizId);

$totalQuestions = count($questions);
$score = 0;

foreach ($questions as $q) {
    $id = $q['id'];
    if (isset($answers[$id]) && $answers[$id] == $q['correct_option']) {
        $score++;
    }
}

$sql = "INSERT INTO results (quiz_id, etudiant_id, score, total_questions)
        VALUES (?, ?, ?, ?)";
Database::getInstance()->query($sql, [
    $quizId,
    $_SESSION['user_id'],
    $score,
    $totalQuestions
]);

unset($_SESSION['answers']);
unset($_SESSION['current_quiz']);

header("Location: ../pages/student/result.php?score=$score&total=$totalQuestions");
exit;
