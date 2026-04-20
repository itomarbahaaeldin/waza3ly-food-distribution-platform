<?php

class TimeSlotsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all time slots
     * 
     * @return array|bool Array of time slots or false on failure
     */
    public function getAllTimeSlots() {
        try {
            // Changed from ORDER BY start_time to ORDER BY id since start_time column doesn't exist
            // According to the schema, time_slots only has id, name, created_at, and updated_at
            $query = "SELECT * FROM time_slots ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting time slots: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get time slot by ID
     * 
     * @param int $id Time slot ID
     * @return array|bool Time slot data or false if not found
     */
    public function getTimeSlotById($id) {
        try {
            $query = "SELECT * FROM time_slots WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting time slot: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get time slot ID by name
     * 
     * @param string $name Time slot name (e.g., "Morning", "Afternoon", "Evening")
     * @return int|bool Time slot ID or false if not found
     */
    public function getTimeSlotIdByName($name) {
        try {
            $query = "SELECT id FROM time_slots WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting time slot ID: " . $e->getMessage());
            return false;
        }
    }
}