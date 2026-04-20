<?php

class AddressModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new address
     * 
     * @param array $addressData Address data including street_address, city_id, governorate_id
     * @return int|bool The ID of the newly created address or false on failure
     */
    public function createAddress($addressData) {
        try {
            $query = "INSERT INTO addresses (street_address, city_id, governorate_id, created_at) 
                      VALUES (:street_address, :city_id, :governorate_id, NOW())";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':street_address', $addressData['street_address']);
            $stmt->bindParam(':city_id', $addressData['city_id'], PDO::PARAM_INT);
            $stmt->bindParam(':governorate_id', $addressData['governorate_id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error creating address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get address by ID
     * 
     * @param int $addressId Address ID
     * @return array|bool Address data or false if not found
     */
    public function getAddressById($addressId) {
        try {
            $query = "SELECT a.*, c.name as city_name, g.name as governorate_name 
                      FROM addresses a
                      JOIN cities c ON a.city_id = c.id
                      JOIN governorates g ON a.governorate_id = g.id
                      WHERE a.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
            $stmt->execute();
            
            $address = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $address ? $address : false;
        } catch (PDOException $e) {
            error_log("Error getting address by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update address information
     * 
     * @param int $addressId Address ID
     * @param array $addressData Address data to update
     * @return bool True on success, false on failure
     */
    public function updateAddress($addressId, $addressData) {
        try {
            $setClause = [];
            $params = [':id' => $addressId];
            
            // Build the SET clause dynamically based on provided data
            foreach ($addressData as $key => $value) {
                if ($key !== 'id') {
                    $setClause[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            // Add updated_at timestamp
            $setClause[] = "updated_at = NOW()";
            
            $query = "UPDATE addresses SET " . implode(', ', $setClause) . " WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an address
     * 
     * @param int $addressId Address ID
     * @return bool True on success, false on failure
     */
    public function deleteAddress($addressId) {
        try {
            $query = "DELETE FROM addresses WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting address: " . $e->getMessage());
            return false;
        }
    }
}