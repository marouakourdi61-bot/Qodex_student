<?php


require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Question.php';


$quizId = $_GET['id'] ?? $_SESSION['current_quiz'] ?? null;
$questionNumber = $_GET['q'] ?? 1;

if (!$quizId) die('Quiz introuvable');


$_SESSION['current_quiz'] = $quizId;

$quizObj = new Quiz();
$questionObj = new Question();


$quiz = $quizObj->getQuizById($quizId);
if (!$quiz) die('Quiz introuvable');


$questions = $questionObj->getQuestionsByQuizId($quizId);
$totalQuestions = count($questions);

if ($questionNumber < 1 || $questionNumber > $totalQuestions) {
    $questionNumber = 1;
}

$currentQuestion = $questions[$questionNumber - 1];
?>

<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<div class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-8">
    <div class="max-w-6xl mx-auto px-6 flex justify-between">
        <div>
            <h1 class="text-3xl font-bold"><?= htmlspecialchars($quiz['titre']) ?></h1>
            <p class="text-green-100">Question <?= $questionNumber ?> sur <?= $totalQuestions ?></p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white shadow-lg rounded-xl p-8">
        <h3 class="text-2xl font-bold mb-6"><?= htmlspecialchars($currentQuestion['question']) ?></h3>

        <form id="quizForm">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <label class="block p-4 mb-3 border rounded-lg cursor-pointer hover:border-green-500">
                    <input type="radio" name="answer" value="<?= $i ?>" class="mr-2"
                    <?php if(isset($_SESSION['answers'][$currentQuestion['id']]) &&
                             $_SESSION['answers'][$currentQuestion['id']] == $i) echo 'checked'; ?>
                    >
                    <?= htmlspecialchars($currentQuestion['option'.$i]) ?>
                </label>
            <?php endfor; ?>
        </form>

        <div class="flex justify-between mt-8">
            <button id="prevBtn" class="px-6 py-3 border rounded-lg" <?= $questionNumber == 1 ? 'disabled' : '' ?>>⬅ Précédent</button>
            <?php if ($questionNumber < $totalQuestions): ?>
                <button id="nextBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg">Suivant ➡</button>
            <?php else: ?>
                <button id="finishBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg">Fin du quiz</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('quizForm');
const radios = form.querySelectorAll('input[name="answer"]');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const finishBtn = document.getElementById('finishBtn');

const currentQ = <?= $questionNumber ?>;
const totalQ = <?= $totalQuestions ?>;

function saveAnswer(questionId, answer) {
    
    if(!/^[1-4]$/.test(answer)) {
        alert("Réponse invalide !");
        return;
    }

    const data = new FormData();
    data.append('question_id', questionId);
    data.append('answer', answer);

    fetch('../../action_student/save_answer.php', {
        method: 'POST',
        body: data,
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status !== 'success') {
            console.error('Erreur:', resp.message);
        } else {
            console.log('Réponse enregistrée', resp.answers);
        }
    })
    .catch(err => console.error(err));
}

radios.forEach(radio => {
    radio.addEventListener('change', function() {
        saveAnswer(<?= $currentQuestion['id'] ?>, this.value);
    });
});

if(nextBtn){
    nextBtn.addEventListener('click', function(e){
        const checked = form.querySelector('input[name="answer"]:checked');
        if(!checked){ alert("Veuillez choisir une réponse !"); return; }
        e.preventDefault();
        saveAnswer(<?= $currentQuestion['id'] ?>, checked.value);
        setTimeout(() => {
            window.location.href = `take_quiz.php?id=<?= $quizId ?>&q=${currentQ + 1}`;
        }, 100);
    });
}

if(prevBtn){
    prevBtn.addEventListener('click', function(e){
        e.preventDefault();
        window.location.href = `take_quiz.php?id=<?= $quizId ?>&q=${currentQ - 1}`;
    });
}

if(finishBtn){
    finishBtn.addEventListener('click', function(e){
        const checked = form.querySelector('input[name="answer"]:checked');
        if(!checked){ alert("Veuillez choisir une réponse !"); return; }
        e.preventDefault();
        saveAnswer(<?= $currentQuestion['id'] ?>, checked.value);
        setTimeout(() => {
            window.location.href = '../../action_student/quiz_submit.php';
        }, 100);
    });
}
</script>
