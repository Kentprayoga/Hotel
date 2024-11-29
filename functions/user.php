<?php
require_once '../functions/db.php'; // Menghubungkan ke class Database
class User extends Database {
    // Metode untuk memeriksa apakah username atau email sudah ada
    public function checkUserExists($username) {
        $query = "SELECT * FROM Users WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Metode untuk mendaftarkan pengguna baru
    public function registerUser($nama, $email, $username, $password, $nomor_hp, $alamat, $role) {
        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $query = "INSERT INTO Users (nama_lengkap, email, username, password, nomor_hp, alamat, role)
                  VALUES (:nama, :email, :username, :password, :nomor_hp, :alamat, :role)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'nama' => $nama,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword,
            'nomor_hp' => $nomor_hp,
            'alamat' => $alamat,
            'role' => $role,
            
        ]);
        
    }
    
}
?>