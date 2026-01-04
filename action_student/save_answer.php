<?php
if(session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['status'=>'error','message'=>'Invalid request method']);
    exit;
}

$questionId = $_POST['question_id'] ?? null;
$answer = $_POST['answer'] ?? null;


if(!$questionId || !preg_match('/^[1-4]$/', $answer)){
    echo json_encode(['status'=>'error','message'=>'Réponse invalide']);
    exit;
}


if(!isset($_SESSION['answers']) || !is_array($_SESSION['answers'])){
    $_SESSION['answers'] = [];
}


$_SESSION['answers'][(int)$questionId] = (int)$answer;

echo json_encode(['status'=>'success','answers'=>$_SESSION['answers']]);
exit;
