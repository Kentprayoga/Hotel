<?php
class Database {
    protected $pdo;

    public function __construct() {
        $host = 'localhost';
        $db = 'grand_kenari'; // Ganti dengan nama database Anda
        $user = 'root'; // Ganti dengan username database Anda
        $pass = ''; // Ganti dengan password database Anda

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>