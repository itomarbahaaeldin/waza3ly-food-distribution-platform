<?php

class LocationsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all governorates/states
     * 
     * @return array|bool Array of governorates or false on failure
     */
    public function getAllGovernorates() {
        try {
            $query = "SELECT * FROM governorates ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all governorates: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get governorate by ID
     * 
     * @param int $governorateId Governorate ID
     * @return array|bool Governorate data or false if not found
     */
    public function getGovernorateById($governorateId) {
        try {
            $query = "SELECT * FROM governorates WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $governorateId, PDO::PARAM_INT);
            $stmt->execute();
            
            $governorate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $governorate ? $governorate : false;
        } catch (PDOException $e) {
            error_log("Error getting governorate by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all cities
     * 
     * @return array|bool Array of cities or false on failure
     */
    public function getAllCities() {
        try {
            $query = "SELECT c.*, g.name as governorate_name 
                      FROM cities c
                      JOIN governorates g ON c.governorate_id = g.id
                      ORDER BY c.name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all cities: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get city by ID
     * 
     * @param int $cityId City ID
     * @return array|bool City data or false if not found
     */
    public function getCityById($cityId) {
        try {
            $query = "SELECT c.*, g.name as governorate_name 
                      FROM cities c
                      JOIN governorates g ON c.governorate_id = g.id
                      WHERE c.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $cityId, PDO::PARAM_INT);
            $stmt->execute();
            
            $city = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $city ? $city : false;
        } catch (PDOException $e) {
            error_log("Error getting city by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cities by governorate ID
     * 
     * @param int $governorateId Governorate ID
     * @return array|bool Array of cities or false on failure
     */
    public function getCitiesByGovernorateId($governorateId) {
        try {
            $query = "SELECT * FROM cities WHERE governorate_id = :governorate_id ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':governorate_id', $governorateId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cities by governorate ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cities as JSON for AJAX requests
     * 
     * @param int $governorateId Governorate ID
     * @return string JSON string of cities
     */
    public function getCitiesAsJson($governorateId) {
        $cities = $this->getCitiesByGovernorateId($governorateId);
        
        if (!$cities) {
            return json_encode([]);
        }
        
        return json_encode($cities);
    }

    /**
     * Get governorates as options for select dropdown
     * 
     * @param int $selectedId Optional selected governorate ID
     * @return string HTML options for select dropdown
     */
    public function getGovernoratesAsOptions($selectedId = null) {
        $governorates = $this->getAllGovernorates();
        $options = '<option value="">Select Governorate</option>';
        
        if ($governorates) {
            foreach ($governorates as $governorate) {
                $selected = ($selectedId == $governorate['id']) ? 'selected' : '';
                $options .= '<option value="' . $governorate['id'] . '" ' . $selected . '>' . htmlspecialchars($governorate['name']) . '</option>';
            }
        }
        
        return $options;
    }

    /**
     * Get cities as options for select dropdown
     * 
     * @param int $governorateId Governorate ID
     * @param int $selectedId Optional selected city ID
     * @return string HTML options for select dropdown
     */
    public function getCitiesAsOptions($governorateId, $selectedId = null) {
        $cities = $this->getCitiesByGovernorateId($governorateId);
        $options = '<option value="">Select City</option>';
        
        if ($cities) {
            foreach ($cities as $city) {
                $selected = ($selectedId == $city['id']) ? 'selected' : '';
                $options .= '<option value="' . $city['id'] . '" ' . $selected . '>' . htmlspecialchars($city['name']) . '</option>';
            }
        }
        
        return $options;
    }
}