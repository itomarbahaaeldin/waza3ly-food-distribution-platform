<?php

class FoodCategoriesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all food categories
     *
     * @return array|false Array of categories or false on failure
     */
    public function getAllFoodCategories() {
        try {
            $query = "SELECT * FROM food_categories ORDER BY id";
            $stmt  = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting food categories: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a single food category by ID
     *
     * @param int $id Category ID
     * @return array|false Category data or false if not found
     */
    public function getFoodCategoryById($id) {
        try {
            $query = "SELECT * FROM food_categories WHERE id = :id";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            error_log("Error getting food category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a food category ID by its name
     *
     * @param string $name Category name
     * @return int|false Category ID or false if not found
     */
    public function getFoodCategoryIdByName($name) {
        try {
            $query = "SELECT id FROM food_categories WHERE name = :name";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();

            $id = $stmt->fetchColumn();
            return $id !== false ? (int)$id : false;
        } catch (PDOException $e) {
            error_log("Error getting food category ID: " . $e->getMessage());
            return false;
        }
    }
}