<?php

class RolesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all roles
     * 
     * @return array|bool Array of roles or false on failure
     */
    public function getAllRoles() {
        try {
            $query = "SELECT * FROM roles ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all roles: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get role by ID
     * 
     * @param int $roleId Role ID
     * @return array|bool Role data or false if not found
     */
    public function getRoleById($roleId) {
        try {
            $query = "SELECT * FROM roles WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $roleId, PDO::PARAM_INT);
            $stmt->execute();
            
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $role ? $role : false;
        } catch (PDOException $e) {
            error_log("Error getting role by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get role by name
     * 
     * @param string $roleName Role name
     * @return array|bool Role data or false if not found
     */
    public function getRoleByName($roleName) {
        try {
            $query = "SELECT * FROM roles WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $roleName);
            $stmt->execute();
            
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $role ? $role : false;
        } catch (PDOException $e) {
            error_log("Error getting role by name: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get role ID by name
     * 
     * @param string $roleName Role name
     * @return int|bool Role ID or false if not found
     */
    public function getRoleIdByName($roleName) {
        $role = $this->getRoleByName($roleName);
        
        return $role ? $role['id'] : false;
    }

    /**
     * Get role name by ID
     * 
     * @param int $roleId Role ID
     * @return string|bool Role name or false if not found
     */
    public function getRoleNameById($roleId) {
        $role = $this->getRoleById($roleId);
        
        return $role ? $role['name'] : false;
    }
}