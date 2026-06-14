<?php
class Course {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllCoursesForUser($user_id) {
        $query = "SELECT c.*, 
                    (SELECT COUNT(*) FROM user_courses uc WHERE uc.course_id = c.id AND uc.user_id = :user_id) AS is_owned
                  FROM courses c 
                  ORDER BY c.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllCoursesForAdmin() {
        $query = "SELECT c.*, COUNT(uc.course_id) AS total_sales
                  FROM courses c
                  LEFT JOIN user_courses uc ON c.id = uc.course_id
                  GROUP BY c.id
                  ORDER BY c.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function purchase($user_id, $course_id) {
       
        $check = $this->conn->prepare("SELECT user_id FROM user_courses WHERE user_id = :user_id AND course_id = :course_id");
        $check->execute([':user_id' => $user_id, ':course_id' => $course_id]);
        
        if ($check->rowCount() > 0) {
            return false; 
        }

        $query = "INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':user_id' => $user_id, ':course_id' => $course_id]);
    }

   
    public function create($title, $description, $price) {
        $query = "INSERT INTO courses (title, description, price) VALUES (:title, :description, :price)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price
        ]);
    }
}
?>