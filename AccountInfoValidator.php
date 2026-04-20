<?php

class TransportationTypesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all transportation types
     * 
     * @return array|bool Array of transportation types or false on failure
     */
    public function getAllTransportationTypes() {
        try {
            $query = "SELECT * FROM transportation_types ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transportation types: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transportation type by ID
     * 
     * @param int $id Transportation type ID
     * @return array|bool Transportation type data or false if not found
     */
    public function getTransportationTypeById($id) {
        try {
            $query = "SELECT * FROM transportation_types WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transportation type: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transportation type ID by name
     * 
     * @param string $name Transportation type name
     * @return int|bool Transportation type ID or false if not found
     */
    public function getTransportationTypeIdByName($name) {
        try {
            $query = "SELECT id FROM transportation_types WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting transportation type ID: " . $e->getMessage());
            return false;
        }
    }
}