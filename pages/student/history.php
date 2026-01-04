<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("Utilisateur non connecté.");
}

$sql = "SELECT r.*, q.titre AS quiz_titre, c.nom AS categorie_nom
        FROM results r
        JOIN quiz q ON q.id = r.quiz_id
        JOIN categories c ON c.id = q.categorie_id
        WHERE r.etudiant_id = ?
        ORDER BY r.id DESC";
$results = Database::getInstance()->query($sql, [$userId])->fetchAll();

$totalQuiz = count($results);
$totalScore = 0;
$totalQuestions = 0;
$quizReussis = 0;

foreach ($results as $res) {
    $totalScore += $res['score'];
    $totalQuestions += $res['total_questions'];
    if (($res['score'] / $res['total_questions']) >= 0.5) {
        $quizReussis++;
    }
}

$moyenne = $totalQuestions ? round(($totalScore / $totalQuestions) * 20, 2) : 0;
$tauxReussite = $totalQuiz ? round(($quizReussis / $totalQuiz) * 100, 2) : 0;

$classement = "#".rand(1,50);
?>

<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-12">

    

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($results as $res): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($res['quiz_titre']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($res['categorie_nom']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold <?= ($res['score'] / $res['total_questions'] >= 0.5) ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $res['score'] ?>/<?= $res['total_questions'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d M Y', strtotime($res['created_at'] ?? date('Y-m-d'))) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= ($res['score'] / $res['total_questions'] >= 0.5) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <i class="fas <?= ($res['score'] / $res['total_questions'] >= 0.5) ? 'fa-check' : 'fa-times' ?> mr-1"></i>
                                <?= ($res['score'] / $res['total_questions'] >= 0.5) ? 'Réussi' : 'Échoué' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
