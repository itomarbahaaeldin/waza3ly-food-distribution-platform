<?php

require_once __DIR__ . '/AddressModel.php';

class UsersModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new user
     * 
     * @param array $userData User data including first_name, last_name, username, email, password, phone, address_id, role_id
     * @return int|bool The ID of the newly created user or false on failure
     */
    public function createUser($userData) {
        try {
            // Hash the password
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (first_name, last_name, username, email, password, phone, address_id, role_id, created_at) 
                      VALUES (:first_name, :last_name, :username, :email, :password, :phone, :address_id, :role_id, NOW())";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':first_name', $userData['first_name']);
            $stmt->bindParam(':last_name', $userData['last_name']);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':phone', $userData['phone']);
            $stmt->bindParam(':address_id', $userData['address_id']);
            $stmt->bindParam(':role_id', $userData['role_id']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            // Log error
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user by ID
     * 
     * @param int $userId User ID
     * @return array|bool User data or false if not found
     */
    public function getUserById($userId) {
        try {
            // First get the basic user data
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return false;
            }
            
            // If user has an address_id, get the address details
            if ($user['address_id']) {
                $addressModel = new AddressModel($this->db);
                $address = $addressModel->getAddressById($user['address_id']);
                
                if ($address) {
                    // Add address details as separate fields in the user object
                    $user['street_address'] = $address['street_address'];
                    $user['city_id'] = $address['city_id'];
                    $user['city_name'] = $address['city_name'];
                    $user['governorate_id'] = $address['governorate_id'];
                    $user['governorate_name'] = $address['governorate_name'];
                    $user['formatted_address'] = $address['street_address'] . ', ' . 
                                               $address['city_name'] . ', ' . 
                                               $address['governorate_name'];
                }
            }
            
            return $user;
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update user information
     * 
     * @param int $userId User ID
     * @param array $userData User data to update
     * @return bool True on success, false on failure
     */
    public function updateUser($userId, $userData) {
        try {
            $setClause = [];
            $params = [':id' => $userId];
            
            // Build the SET clause dynamically based on provided data
            foreach ($userData as $key => $value) {
                if ($key !== 'id' && $key !== 'password') {
                    $setClause[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            // Handle password separately if it's being updated
            if (isset($userData['password'])) {
                $setClause[] = "password = :password";
                $params[':password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            }
            
            // Add updated_at timestamp
            $setClause[] = "updated_at = NOW()";
            
            $query = "UPDATE users SET " . implode(', ', $setClause) . " WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user
     * 
     * @param int $userId User ID
     * @return bool True on success, false on failure
     */
    public function deleteUser($userId) {
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if username exists
     * 
     * @param string $username Username to check
     * @return bool True if username exists, false otherwise
     */
    public function usernameExists($username)
    {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if username exists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     * 
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists($email) {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if email exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if phone number exists
     * 
     * @param string $phone Phone number to check
     * @return bool True if phone number exists, false otherwise
     */
    public function phoneExists($phone) {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE phone = :phone";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if phone number exists: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Find user by email or username
     * 
     * @param string $identifier Email or username
     * @return array|bool User data if found, false otherwise
     */
    public function findUserByEmailOrUsername($identifier) {
        try {
            // Determine if identifier is email or username
            $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
            
            // Get user by email or username
            if ($isEmail) {
                $query = "SELECT * FROM users WHERE email = :identifier";
            } else {
                $query = "SELECT * FROM users WHERE username = :identifier";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':identifier', $identifier);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user ? $user : false;
        } catch (PDOException $e) {
            error_log("Error finding user by email or username: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify password for a user
     * 
     * @param string $password Plain text password to verify
     * @param string $hashedPassword Hashed password from database
     * @return bool True if password is correct, false otherwise
     */
    public function verifyPassword($password, $hashedPassword) {
        // Trim the user’s input (to strip stray spaces or newlines)
        $password = trim($password);

        // Verify
        return password_verify($password, $hashedPassword);
    }
    /**
     * Get all users
     * 
     * @return array|false An array of all users or false on failure
     */
    public function getAllUsers() {
        try {
            $query = "SELECT id, first_name, last_name, username, email, phone, role_id, created_at FROM users";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error fetching all users: " . $e->getMessage());
            return false; 
        }
    }
}