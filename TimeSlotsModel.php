<?php

class RequestItemsModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all items for a specific request
     *
     * @param int $requestId
     * @return array|false Array of items or false on failure
     */
    public function getItemsByRequestId(int $requestId) {
        try {
            $query = "
                SELECT
                    ri.id,
                    ri.category_id,
                    fc.name AS category_name,
                    ri.food_name,
                    ri.quantity,
                    ri.unit,
                    ri.created_at
                FROM request_items ri
                JOIN food_categories fc ON ri.category_id = fc.id
                WHERE ri.request_id = :request_id
                ORDER BY ri.id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching request items: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a single request item
     *
     * @param int    $requestId
     * @param int    $categoryId
     * @param string $foodName
     * @param float  $quantity
     * @param string $unit
     * @return int|false Inserted item ID or false on failure
     */
    public function createItem(int $requestId, int $categoryId, string $foodName, float $quantity, string $unit) {
        try {
            $query = "
                INSERT INTO request_items
                    (request_id, category_id, food_name, quantity, unit, created_at)
                VALUES
                    (:request_id, :category_id, :food_name, :quantity, :unit, NOW())
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':request_id',  $requestId,          PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $categoryId,         PDO::PARAM_INT);
            $stmt->bindParam(':food_name',   $foodName,           PDO::PARAM_STR);
            // Format quantity to match DECIMAL(10,2)
            $stmt->bindValue(':quantity',    number_format($quantity, 2, '.', ''), PDO::PARAM_STR);
            $stmt->bindParam(':unit',        $unit,               PDO::PARAM_STR);
            $stmt->execute();
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating request item: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Delete all items for a specific request
     *
     * @param int $requestId
     * @return bool True on success, false on failure
     */
    public function deleteItemsByRequestId(int $requestId): bool {
        try {
            $query = "DELETE FROM request_items WHERE request_id = :request_id";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting request items: " . $e->getMessage());
            return false;
        }
    }
}