<?php

class AdminModel {
    private $pdo;

    public function __construct(PDO $db) {
        $this->pdo = $db;
    }


    /**
     * Find admin by email or username
     *
     * @param string $identifier The email or username
     * @return array|false The admin data if found, or false if not
     */
    public function findAdminByEmail($identifier) {
        $stmt = $this->pdo->prepare("SELECT id, email, password FROM admins WHERE email = :identifier");
        $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Verify the password
     *
     * @param string $password The plain password
     * @param string $hashedPassword The hashed password in the database
     * @return bool True if the password is correct, false otherwise
     */
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
?>