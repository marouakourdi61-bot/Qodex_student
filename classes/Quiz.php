<?php
/**
 * Classe Quiz
 * Gère les opérations CRUD sur les quiz
 */

class Quiz {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); 
    }

     
    

    // Créer un quiz
    public function create($titre, $description, $categorieId, $enseignantId) {
        if (empty($titre) || empty($categorieId) || empty($enseignantId)) {
            return false;
        }

        $sql = "INSERT INTO quiz (titre, description, categorie_id, enseignant_id) 
                VALUES (?, ?, ?, ?)";

        try {
            $this->db->query($sql, [$titre, $description, $categorieId, $enseignantId]);
            return $this->db->getConnection()->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }

    // Tous les quiz d’un enseignant
    public function getAllByTeacher($teacherId) {
        $sql = "SELECT q.*, c.nom AS categorie_nom,
                       COUNT(DISTINCT qu.id) AS questions_count,
                       COUNT(DISTINCT r.id) AS participants_count
                FROM quiz q
                LEFT JOIN categories c ON q.categorie_id = c.id
                LEFT JOIN questions qu ON q.id = qu.quiz_id
                LEFT JOIN results r ON q.id = r.quiz_id
                WHERE q.enseignant_id = ?
                GROUP BY q.id
                ORDER BY q.created_at DESC";

        $result = $this->db->query($sql, [$teacherId]);
        return $result->fetchAll();
    }

    // Quiz par ID (enseignant)
    public function getById($id) {
        $sql = "SELECT q.*, c.nom AS categorie_nom
                FROM quiz q
                LEFT JOIN categories c ON q.categorie_id = c.id
                WHERE q.id = ?";

        $result = $this->db->query($sql, [$id]);
        return $result->fetch();
    }

    // Vérifier le propriétaire
    public function isOwner($quizId, $teacherId) {
        $sql = "SELECT id FROM quiz WHERE id = ? AND enseignant_id = ?";
        $result = $this->db->query($sql, [$quizId, $teacherId]);
        return $result->rowCount() > 0;
    }

    // Mettre à jour un quiz
    public function update($id, $titre, $description, $categorieId, $teacherId) {
        if (!$this->isOwner($id, $teacherId)) {
            return false;
        }

        $sql = "UPDATE quiz 
                SET titre = ?, description = ?, categorie_id = ? 
                WHERE id = ?";

        try {
            $this->db->query($sql, [$titre, $description, $categorieId, $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Activer / désactiver
    public function toggleActive($id, $isActive, $teacherId) {
        if (!$this->isOwner($id, $teacherId)) {
            return false;
        }

        $sql = "UPDATE quiz SET is_active = ? WHERE id = ?";
        try {
            $this->db->query($sql, [$isActive, $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Supprimer un quiz
    public function delete($id, $teacherId) {
        if (!$this->isOwner($id, $teacherId)) {
            return false;
        }

        $sql = "DELETE FROM quiz WHERE id = ?";
        try {
            $this->db->query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Statistiques
    public function getStats($quizId) {
        $sql = "SELECT 
                    COUNT(DISTINCT r.id) AS total_attempts,
                    AVG(r.score / r.total_questions * 100) AS avg_score,
                    COUNT(DISTINCT r.etudiant_id) AS unique_students
                FROM results r
                WHERE r.quiz_id = ?";

        $result = $this->db->query($sql, [$quizId]);
        return $result->fetch();
    }










    

    
    //ETUDIANT 
    

    // Quiz 
    public function getQuizById($quizId) {
    $sql = "SELECT * FROM quiz
            WHERE id = ?
            AND is_active = 1";

    $result = $this->db->query($sql, [$quizId]);
    return $result->fetch();
}


    // Questions 
    public function getQuestionsByQuizId($quizId) {
    $sql = "SELECT * FROM questions
            WHERE quiz_id = ?
            ORDER BY id ASC";

    $result = $this->db->query($sql, [$quizId]);
    return $result->fetchAll();
}

}
