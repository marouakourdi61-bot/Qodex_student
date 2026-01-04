<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';

$categoryObj = new Category();


$categoryId = $_GET['category'] ?? null;

$categoryQuizzes = [];
$categoryName = '';

if ($categoryId) {
    $quizzes = $categoryObj->getCategoryQuizzes($categoryId);
    if ($quizzes) {
        $categoryQuizzes = $quizzes;
        $categoryName = $quizzes[0]['nom']; 
    }

    
}
?>


<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<div id="categoryQuizzes" class="student-section">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <button onclick="showStudentSection('studentDashboard')" class="text-white hover:text-green-100 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux catégories
            </button>
            <h1 class="text-4xl font-bold mb-2" id="categoryTitle">HTML/CSS</h1>
            <p class="text-xl text-green-100">Sélectionnez un quiz pour commencer</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div id="quizListContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Quiz cards will be loaded dynamically -->
        </div>
    </div>

 <!-- Active Quizzes -->
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center justify-center">
                <span class="w-2 h-8 bg-green-500 rounded-full mr-3"></span>
                Quiz Actifs
            </h2>

            <div class="space-y-4 flex flex-col items-center">
                <?php if (!empty($categoryQuizzes)): ?>
    <?php foreach($categoryQuizzes as $q): ?>
        <div class="bg-white p-5 rounded-xl shadow-sm border flex flex-col md:flex-row justify-between hover:shadow-md transition w-full max-w-2xl">

            <div class="flex items-center space-x-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                    <i class="fas fa-terminal"></i>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800">
                        <?= htmlspecialchars($q['titre']) ?>
                    </h4>

                    <div class="flex space-x-4 mt-1 text-xs text-gray-500">
                        <span>
                            <i class="far fa-folder mr-1"></i>
                            <?= htmlspecialchars($q['categorie_nom']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="take_quiz.php?id=<?= $q['id'] ?>"
                   class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    Commencer
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-gray-500">Aucun quiz disponible pour cette catégorie.</p>
<?php endif; ?>

            </div>
        </div>

</div>
