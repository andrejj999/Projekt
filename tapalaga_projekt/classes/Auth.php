<?php
class Auth {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
    // 1. Skontrolujeme, či už užívateľ s týmto emailom neexistuje
    $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return "Tento email už je zaregistrovaný!";
    }

    // 2. Bezpečne zahešujeme heslo (presne podľa požiadaviek na plusové body)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_role = 'user'; // Každý nový užívateľ bude bežný 'user'

    // 3. Vložíme nového užívateľa do databázy
    $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $default_role);

    if ($stmt->execute()) {
        return true; // Registrácia prebehla úspešne
    }

    return "Uuups, niečo sa pokazilo pri zápise do databázy.";
}
    public function login($email, $password) {
        $query = "SELECT id, username, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Overenie zahashovaného hesla (požiadavka na plusové body)
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['logged_in'] = true;
                return true;
            }
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function isAdmin() {
        return $this->isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}
?>