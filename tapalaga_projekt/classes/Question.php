<?php
class Question {
    private $conn;
    private $table_name = "questions";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE: Používateľ pridá otázku
    public function create($user_id, $question_text) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, question_text) VALUES (:user_id, :question_text)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':question_text', $question_text);

        return $stmt->execute();
    }

    // READ: Načítanie všetkých otázok spolu s menom užívateľa a admina, ktorý odpovedal
    public function readAll() {
        $query = "SELECT q.id, q.question_text, q.answer_text, q.created_at, 
                         u.username AS author, a.username AS admin_name 
                  FROM " . $this->table_name . " q
                  JOIN users u ON q.user_id = u.id
                  LEFT JOIN users a ON q.admin_id = a.id
                  ORDER BY q.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE: Admin odpovedá na otázku
    public function answer($question_id, $admin_id, $answer_text) {
        $query = "UPDATE " . $this->table_name . " 
                  SET answer_text = :answer_text, admin_id = :admin_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':answer_text', $answer_text);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':id', $question_id);

        return $stmt->execute();
    }

    // DELETE: Admin (alebo autor) môže otázku vymazať
    public function delete($question_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $question_id);
        return $stmt->execute();
    }
}
?>