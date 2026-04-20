<?php
require_once __DIR__ . '/../models/LocationsModel.php';

class LocationsController {
    private $locationsModel;
    
    public function __construct($db) {
        $this->locationsModel = new LocationsModel($db);
    }
    
    public function getCitiesByGovernorate() {
        // Set headers for JSON response
        header('Content-Type: application/json');
        
        // Check if governorate_id is provided
        if (!isset($_GET['governorate_id']) || empty($_GET['governorate_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Governorate ID is required']);
            return;
        }
        
        $governorateId = $_GET['governorate_id'];
        
        // Validate governorate_id is numeric
        if (!is_numeric($governorateId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid Governorate ID']);
            return;
        }
        
        try {
            // Get cities by governorate ID
            $cities = $this->locationsModel->getCitiesByGovernorateId($governorateId);
            
            // Return cities as JSON
            echo json_encode($cities);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch cities: ' . $e->getMessage()]);
        }
    }
}