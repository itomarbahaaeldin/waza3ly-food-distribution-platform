<?php

class DaysModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all days
     * 
     * @return array|bool Array of days or false on failure
     */
    public function getAllDays() {
        try {
            $query = "SELECT * FROM days ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting days: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get day by ID
     * 
     * @param int $id Day ID
     * @return array|bool Day data or false if not found
     */
    public function getDayById($id) {
        try {
            $query = "SELECT * FROM days WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting day: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get day ID by name
     * 
     * @param string $name Day name (e.g., "Monday", "Tuesday", etc.)
     * @return int|bool Day ID or false if not found
     */
    public function getDayIdByName($name) {
        try {
            $query = "SELECT id FROM days WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting day ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Convert an array of day names to day IDs
     * 
     * @param array $dayNames Array of day names (e.g., ["Monday", "Wednesday", "Friday"])
     * @return array Array of day IDs corresponding to the provided names
     */
    public function convertDayNamesToIds($dayNames) {
        if (empty($dayNames)) {
            return [];
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($dayNames), '?'));
            $query = "SELECT id, name FROM days WHERE name IN ($placeholders)";
            
            $stmt = $this->db->prepare($query);
            
            // Bind each day name as a parameter
            foreach ($dayNames as $index => $name) {
                $stmt->bindValue($index + 1, $name);
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Create a mapping of names to IDs
            $dayIds = [];
            foreach ($results as $row) {
                $dayIds[] = $row['id'];
            }
            
            return $dayIds;
        } catch (PDOException $e) {
            error_log("Error converting day names to IDs: " . $e->getMessage());
            return [];
        }
    }
}