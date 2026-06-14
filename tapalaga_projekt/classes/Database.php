<?php
class Database {
    private $host = "localhost";
    private $db_name = "linux_distro";
    private $username = "root"; // Zmeň, ak máš iné meno
    private $password = "";     // Zmeň, ak máš heslo na localhoste
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            // Nastavenie PDO na vyhadzovanie výnimiek pri chybách
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Chyba pripojenia k databáze: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>