<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';



$categoryObj = new Category();
$categories = $categoryObj->getAllcountcategorie();
if (!$categories) {
    $categories = [];
}
?>

<?php include '../partials/header.php'; ?>
<?php include 'nav.php'; ?>

<!-- Student Dashboard -->
<div id="studentDashboard" class="student-section">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold mb-4">Espace Étudiant</h1>
            <p class="text-xl text-green-100 mb-6">Passez des quiz et suivez votre progression</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Catégories Disponibles</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($categories as $cat): ?>
                <div onclick="window.location.href='quizzes.php?category=<?= $cat['id'] ?>'" class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group cursor-pointer">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                        <i class="fas fa-folder text-4xl mb-3"></i>
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($cat['nom']) ?></h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($cat['description']) ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i><?= $cat['quiz_count'] ?> quiz</span>
                            <span class="text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">Explorer →</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
