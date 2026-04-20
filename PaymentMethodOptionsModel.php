<?php

class DonationFrequenciesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all donation frequencies
     *
     * @return array|bool Array of donation frequencies or false on failure
     */
    public function getAllDonationFrequencies() {
        try {
            $query = "SELECT * FROM donation_frequencies ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting donation frequencies: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a single donation frequency by ID
     *
     * @param int $id Donation frequency ID
     * @return array|bool Donation frequency data or false if not found
     */
    public function getDonationFrequencyById($id) {
        try {
            $query = "SELECT * FROM donation_frequencies WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            error_log("Error getting donation frequency: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get donation frequency ID by name
     *
     * @param string $name Donation frequency name
     * @return int|bool Donation frequency ID or false if not found
     */
    public function getDonationFrequencyIdByName($name) {
        try {
            $query = "SELECT id FROM donation_frequencies WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $id = $stmt->fetchColumn();
            return $id !== false ? (int)$id : false;
        } catch (PDOException $e) {
            error_log("Error getting donation frequency ID: " . $e->getMessage());
            return false;
        }
    }
}