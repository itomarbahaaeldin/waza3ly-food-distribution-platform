<?php

class PickupAddressModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new pickup address
     * 
     * @param array $addressData ['street_address'=>string, 'city_id'=>int, 'governorate_id'=>int]
     * @return int|bool Inserted pickup_address ID or false on failure
     */
    public function createPickupAddress(array $addressData) {
        try {
            $sql = "
                INSERT INTO pickup_addresses
                    (street_address, city_id, governorate_id, created_at)
                VALUES
                    (:street_address, :city_id, :governorate_id, NOW())
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':street_address',  $addressData['street_address'], PDO::PARAM_STR);
            $stmt->bindParam(':city_id',         $addressData['city_id'],       PDO::PARAM_INT);
            $stmt->bindParam(':governorate_id',  $addressData['governorate_id'],PDO::PARAM_INT);

            if ($stmt->execute()) {
                return (int)$this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating pickup address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a pickup address by ID
     * 
     * @param int $id
     * @return array|false Address row or false if not found
     */
    public function getPickupAddressById(int $id) {
        try {
            $sql = "
                SELECT pa.*, c.name AS city_name, g.name AS governorate_name
                  FROM pickup_addresses pa
                  JOIN cities c        ON pa.city_id = c.id
                  JOIN governorates g  ON pa.governorate_id = g.id
                 WHERE pa.id = :id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: false;
        } catch (PDOException $e) {
            error_log("Error fetching pickup address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a pickup address
     * 
     * @param int   $id
     * @param array $addressData any of ['street_address','city_id','governorate_id']
     * @return bool
     */
    public function updatePickupAddress(int $id, array $addressData) {
        try {
            $sets   = [];
            $params = [':id' => $id];

            foreach ($addressData as $col => $val) {
                if (in_array($col, ['street_address','city_id','governorate_id'], true)) {
                    $sets[]           = "`$col` = :$col";
                    $params[":$col"]  = $val;
                }
            }
            // always update timestamp
            $sets[] = "updated_at = NOW()";

            $sql = "UPDATE pickup_addresses
                       SET " . implode(', ', $sets) . "
                     WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $p => $v) {
                $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating pickup address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a pickup address
     * 
     * @param int $id
     * @return bool
     */
    public function deletePickupAddress(int $id) {
        try {
            $sql = "DELETE FROM pickup_addresses WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting pickup address: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Return a formatted string for a pickup address
     *
     * @param int $id
     * @return string|false
     */
    public function formatPickupAddress(int $id) {
        $addr = $this->getPickupAddressById($id);
        if (!$addr) {
            return false;
        }
        return sprintf(
            "%s, %s, %s",
            $addr['street_address'],
            $addr['city_name'],
            $addr['governorate_name']
        );
    }
}