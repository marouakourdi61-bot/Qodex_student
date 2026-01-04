<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';

$score = $_GET['score'] ?? null;
$total = $_GET['total'] ?? null;

if (!$score || !$total) {
    die('Score introuvable.');
}


$passingScore = 0.5; 
$taux = ($score / $total) * 100;
$status = ($score / $total) >= $passingScore ? 'Réussi' : 'Échoué';
?>

<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-4xl font-bold mb-4">Résultat du Quiz</h1>
    <p class="text-xl mb-6">Votre score : <?= htmlspecialchars($score) ?> / <?= htmlspecialchars($total) ?></p>
    <p class="text-2xl font-semibold <?= $status === 'Réussi' ? 'text-green-600' : 'text-red-600' ?>">
        <?= $status ?>
    </p>
    <p class="text-lg mt-2">Taux de réussite : <?= round($taux, 2) ?>%</p>

    <a href="dashboard.php" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
        Retour au tableau de bord
    </a>
</div>
