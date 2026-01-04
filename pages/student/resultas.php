<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';

if(!isset($_SESSION['user_id'])){
    die('Utilisateur non connecté.');
}

$userId = $_SESSION['user_id'];


$sql = "SELECT score, total_questions FROM results WHERE etudiant_id = ?";
$results = Database::getInstance()->query($sql, [$userId])->fetchAll();

$totalQuizzes = count($results);
$totalScore = 0;
$totalQuestions = 0;

foreach($results as $r){
    $totalScore += $r['score'];
    $totalQuestions += $r['total_questions'];
}

$moyenne = $totalQuestions ? round(($totalScore / $totalQuestions) * 20, 2) : 0; 
$tauxReussite = $totalQuestions ? round(($totalScore / $totalQuestions) * 100, 2) : 0;


$sqlRank = "SELECT etudiant_id, SUM(score)/SUM(total_questions) AS taux FROM results GROUP BY etudiant_id ORDER BY taux DESC";
$ranks = Database::getInstance()->query($sqlRank)->fetchAll();
$classement = 1;
foreach($ranks as $r){
    if($r['etudiant_id'] == $userId) break;
    $classement++;
}
?>

<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<div id="studentResults" class="student-section">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="dashboard.php" class="text-white hover:text-green-100 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au tableau de bord
            </a>
            <h1 class="text-4xl font-bold mb-2">Mes Résultats</h1>
            <p class="text-xl text-green-100">Suivez votre progression et vos performances</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Quiz Complétés</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $totalQuizzes ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Moyenne</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $moyenne ?> / 20</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-star text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Taux de Réussite</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $tauxReussite ?>%</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Classement</p>
                        <p class="text-3xl font-bold text-gray-900">#<?= $classement ?></p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
