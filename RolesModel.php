<?php

class PickupTimesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all pickup time options
     *
     * @return array|bool Array of pickup times or false on failure
     */
    public function getAllPickupTimes() {
        try {
            $query = "SELECT * FROM pickup_times ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting pickup times: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a single pickup time by ID
     *
     * @param int $id Pickup time ID
     * @return array|bool Pickup time data or false if not found
     */
    public function getPickupTimeById($id) {
        try {
            $query = "SELECT * FROM pickup_times WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            error_log("Error getting pickup time: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get pickup time ID by name
     *
     * @param string $name Pickup time name
     * @return int|bool Pickup time ID or false if not found
     */
    public function getPickupTimeIdByName($name) {
        try {
            $query = "SELECT id FROM pickup_times WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $id = $stmt->fetchColumn();
            return $id !== false ? (int)$id : false;
        } catch (PDOException $e) {
            error_log("Error getting pickup time ID: " . $e->getMessage());
            return false;
        }
    }
}