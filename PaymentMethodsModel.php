<?php

require_once __DIR__ . '/DonationFrequenciesModel.php';
require_once __DIR__ . '/PickupTimesModel.php';

class DonorsModel {
    private $db;
    private $freqModel;
    private $ptModel;

    public function __construct($db) {
        $this->db        = $db;
        $this->freqModel = new DonationFrequenciesModel($db);
        $this->ptModel   = new PickupTimesModel($db);
    }

    /**
     * Create a complete donor profile
     *
     * @param array $donorData [
     *   'user_id'               => int,
     *   'donation_frequency_id' => int,
     *   'typical_quantity'      => string,
     *   'pickup_time_id'        => int|null,
     *   'additional_info'       => string|null
     * ]
     * @return int|bool New donor ID or false on failure
     */
    public function createDonorProfile(array $donorData) {
        try {
            $query = "
                INSERT INTO donors
                    (user_id, donation_frequency_id, typical_quantity, pickup_time_id, additional_info, created_at)
                VALUES
                    (:user_id, :donation_frequency_id, :typical_quantity, :pickup_time_id, :additional_info, NOW())
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id',               $donorData['user_id'],               PDO::PARAM_INT);
            $stmt->bindParam(':donation_frequency_id', $donorData['donation_frequency_id'], PDO::PARAM_INT);
            $stmt->bindParam(':typical_quantity',      $donorData['typical_quantity']);
            $stmt->bindParam(':pickup_time_id',        $donorData['pickup_time_id'],        PDO::PARAM_INT);
            $stmt->bindParam(':additional_info',       $donorData['additional_info']);
            $stmt->execute();

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating donor profile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch a donor profile by user ID
     *
     * @param int $userId
     * @return array|bool Profile data or false if not found
     */
    public function getDonorProfile(int $userId) {
        try {
            // 1. Base record
            $query = "SELECT * FROM donors WHERE user_id = :user_id";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $donor = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$donor) return false;

            // 2. Donation frequency name
            $freq = $this->freqModel->getDonationFrequencyById($donor['donation_frequency_id']);
            $donor['donation_frequency_name'] = $freq['name'] ?? null;

            // 3. Pickup time name
            if ($donor['pickup_time_id']) {
                $pt = $this->ptModel->getPickupTimeById($donor['pickup_time_id']);
                $donor['pickup_time_name'] = $pt['name'] ?? null;
            }

            return $donor;
        } catch (PDOException $e) {
            error_log("Error fetching donor profile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get donor ID by user ID
     *
     * @param int $userId
     * @return int|bool donor.id or false if none
     */
    public function getDonorIdByUserId(int $userId) {
        try {
            $query = "SELECT id FROM donors WHERE user_id = :user_id";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['id'] : false;
        } catch (PDOException $e) {
            error_log("Error getting donor ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a donor profile
     *
     * @param int   $userId
     * @param array $donorData [
     *   'donation_frequency_id' => int,
     *   'typical_quantity'      => string,
     *   'pickup_time_id'        => int|null,
     *   'additional_info'       => string|null
     * ]
     * @return bool
     */
    public function updateDonorProfile(int $userId, array $donorData) {
        try {
            $this->db->beginTransaction();

            $donorId = $this->getDonorIdByUserId($userId);
            if (!$donorId) {
                $this->db->rollBack();
                return false;
            }

            $query = "
                UPDATE donors SET
                    donation_frequency_id = :donation_frequency_id,
                    typical_quantity      = :typical_quantity,
                    pickup_time_id        = :pickup_time_id,
                    additional_info       = :additional_info,
                    updated_at = NOW()
                WHERE id = :donor_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':donation_frequency_id', $donorData['donation_frequency_id'], PDO::PARAM_INT);
            $stmt->bindParam(':typical_quantity',      $donorData['typical_quantity']);
            $stmt->bindParam(':pickup_time_id',        $donorData['pickup_time_id'], PDO::PARAM_INT);
            $stmt->bindParam(':additional_info',       $donorData['additional_info']);
            $stmt->bindParam(':donor_id',              $donorId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating donor profile: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Delete a donor profile by user ID
     * 
     * @param int $userId
     * @return bool True on success, false on failure
     */
    public function deleteDonorProfile(int $userId) {
        try {
            $this->db->beginTransaction();
            
            // Get donor ID first
            $donorId = $this->getDonorIdByUserId($userId);
            if (!$donorId) {
                $this->db->rollBack();
                return false;
            }
            
            // Delete the donor record
            $query = "DELETE FROM donors WHERE id = :donor_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':donor_id', $donorId, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            if (!$result) {
                $this->db->rollBack();
                return false;
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error deleting donor profile: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Get user ID by donor ID
     *
     * @param int $donorId
     * @return int|bool user.id or false if not found
     */
    public function getUserIdByDonorId(int $donorId) {
        try {
            $query = "SELECT user_id FROM donors WHERE id = :donor_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':donor_id', $donorId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['user_id'] : false;
        } catch (PDOException $e) {
            error_log("Error getting user ID by donor ID: " . $e->getMessage());
            return false;
        }
    }
}