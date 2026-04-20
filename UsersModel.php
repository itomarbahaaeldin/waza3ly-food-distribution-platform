<?php

class RequestStatusesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all statuses from the request_statuses table
     *
     * @return array|false Array of statuses, or false on failure
     */
    public function getAllStatuses() {
        try {
            $query = "SELECT id, name FROM request_statuses ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all statuses: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the status name by its ID
     *
     * @param int $statusId
     * @return string|false Status name, or false if not found
     */
    public function getStatusNameById(int $statusId) {
        try {
            $query = "SELECT name FROM request_statuses WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $statusId, PDO::PARAM_INT);
            $stmt->execute();
            $status = $stmt->fetch(PDO::FETCH_ASSOC);
            return $status ? $status['name'] : false;
        } catch (PDOException $e) {
            error_log("Error fetching status name by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the status ID by its name
     *
     * @param string $statusName
     * @return int|false Status ID, or false if not found
     */
    public function getStatusIdByName(string $statusName) {
        try {
            $query = "SELECT id FROM request_statuses WHERE name = :name LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $statusName, PDO::PARAM_STR);
            $stmt->execute();
            $status = $stmt->fetch(PDO::FETCH_ASSOC);
            return $status ? $status['id'] : false;
        } catch (PDOException $e) {
            error_log("Error fetching status ID by name: " . $e->getMessage());
            return false;
        }
    }
}
?>