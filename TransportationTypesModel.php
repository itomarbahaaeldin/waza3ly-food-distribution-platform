<?php

class RequestsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    /**
     * Fetch all requests (regardless of donor ID) and include the donor ID
     *
     * @return array|false Array of requests, or false on failure
     */
    public function getAllRequests() {
        try {
            $query = "
                SELECT
                r.id,
                r.name,
                r.description,
                r.pickup_address_id,
                r.scheduled_pickup,
                r.created_at,
                r.donor_id,
                rs.name AS status_name
                FROM requests r
                JOIN request_statuses rs ON r.status_id = rs.id
                ORDER BY r.created_at DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all requests: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all requests made by a given donor
     *
     * @param int $donorId
     * @return array|false Array of requests (without items), or false on failure
     */
    public function getRequestsByDonorId(int $donorId) {
        try {
            $query = "
            SELECT DISTINCT
            r.id,
            r.name,
            r.description,
            r.pickup_address_id,
            r.scheduled_pickup,
            r.created_at,
            rs.name AS status_name
            FROM requests r
            JOIN request_statuses rs ON r.status_id = rs.id
            WHERE r.donor_id = :donor_id
            ORDER BY r.created_at DESC
            ";        
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':donor_id', $donorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching requests by donor: " . $e->getMessage());
            return false;
        }
    }

    public function createRequest(
        int $donorId,
        int $pickupAddressId,
        int $statusId,
        string $name,
        ?string $description,
        string $scheduledPickup
    ) {
        try {
            $this->db->beginTransaction();
    
            $insert = "
                INSERT INTO requests
                  (donor_id, pickup_address_id, status_id, name, description, scheduled_pickup, created_at)
                VALUES
                  (:donor_id, :pickup_address_id, :status_id, :name, :description, :scheduled_pickup, NOW())
            ";
            $stmt = $this->db->prepare($insert);
            $stmt->bindParam(':donor_id',          $donorId,           PDO::PARAM_INT);
            $stmt->bindParam(':pickup_address_id', $pickupAddressId,   PDO::PARAM_INT);
            $stmt->bindParam(':status_id',         $statusId,          PDO::PARAM_INT);
            $stmt->bindParam(':name',              $name,              PDO::PARAM_STR);
            $stmt->bindParam(':description',       $description,       PDO::PARAM_STR);
            $stmt->bindParam(':scheduled_pickup',  $scheduledPickup,   PDO::PARAM_STR);
            $stmt->execute();
    
            $requestId = (int)$this->db->lastInsertId();
            $this->db->commit();
    
            return $requestId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating request: " . $e->getMessage());
            return false;
        }
    }    

    /**
     * Fetch a single request by its ID (without items, items are fetched separately)
     *
     * @param int $requestId
     * @return array|false
     */
    public function getRequestById(int $requestId) {
        try {
            $query = "
                SELECT
                r.id,
                r.name,
                r.description,
                r.pickup_address_id,
                r.scheduled_pickup,
                r.created_at,
                r.donor_id, 
                rs.name AS status_name
                FROM requests r
                JOIN request_statuses rs ON r.status_id = rs.id
                WHERE r.id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
            $stmt->execute();
            $req = $stmt->fetch(PDO::FETCH_ASSOC);

            return $req ?: false;
        } catch (PDOException $e) {
            error_log("Error fetching request by id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a request (does not delete its items—leave that to RequestItemsModel)
     *
     * @param int $requestId
     * @return bool
     */
    public function deleteRequest(int $requestId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM requests WHERE id = :id");
            $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting request: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update the status of a request
     *
     * @param int $requestId
     * @param int $statusId
     * @return bool True on success, false on failure
     */
    public function updateRequestStatus(int $requestId, int $statusId) {
        try {
            $query = "
                UPDATE requests
                SET status_id = :status_id, updated_at = NOW()
                WHERE id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status_id', $statusId, PDO::PARAM_INT);
            $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating request status: " . $e->getMessage());
            return false;
        }
    }
}