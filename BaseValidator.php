<?php

class VolunteerAssignmentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new volunteer assignment
     *
     * @param int    $volunteerId
     * @param int    $requestId
     * @return int|false New assignment ID or false on failure
     */
    public function createAssignment(int $volunteerId, int $requestId) {
        try {
            // Insert a new volunteer assignment record into the volunteer_assignments table
            $query = "
                INSERT INTO volunteer_assignments (volunteer_id, request_id, assigned_at)
                VALUES (:volunteer_id, :request_id, NOW())
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating volunteer assignment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all volunteer assignments for a specific volunteer
     *
     * @param int $volunteerId
     * @return array|false Array of assignments, or false on failure
     */
    public function getAssignmentsByVolunteerId(int $volunteerId) {
        try {
            $query = "
                SELECT
                    va.id,
                    va.volunteer_id,
                    va.request_id,
                    va.assigned_at,
                    r.scheduled_pickup,
                    r.status_id AS request_status_id,
                    rs.name AS status_name
                FROM volunteer_assignments va
                JOIN requests r ON va.request_id = r.id
                JOIN request_statuses rs ON r.status_id = rs.id
                WHERE va.volunteer_id = :volunteer_id
                ORDER BY va.assigned_at DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching assignments by volunteer ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all volunteer assignments for a specific request
     *
     * @param int $requestId
     * @return array|false Array of assignments, or false on failure
     */
    public function getAssignmentsByRequestId(int $requestId) {
        try {
            $query = "
                SELECT
                    va.id,
                    va.volunteer_id,
                    va.assigned_at,
                    v.first_name AS volunteer_first_name,
                    v.last_name AS volunteer_last_name
                FROM volunteer_assignments va
                JOIN volunteers v ON va.volunteer_id = v.id
                WHERE va.request_id = :request_id
                ORDER BY va.assigned_at DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching assignments by request ID: " . $e->getMessage());
            return false;
        }
    }
}

?>