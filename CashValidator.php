<?php

class VolunteerModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a complete volunteer profile including all related data
     * 
     * @param array $volunteerProfileData Volunteer profile data (user_id, transportation_type_id, service_radius, experience, time_slot_id)
     * @param array $availabilityData Array of day IDs the volunteer is available
     * @return int|bool The ID of the newly created volunteer profile or false on failure
     */
    public function createVolunteerProfile($volunteerProfileData, $availabilityData) {
        try {
            // 1. Create the base volunteer profile
            $query = "INSERT INTO volunteers (user_id, transportation_type_id, service_radius, experience, preferred_time_slot_id, created_at) 
                      VALUES (:user_id, :transportation_type_id, :service_radius, :experience, :preferred_time_slot_id, NOW())";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':user_id', $volunteerProfileData['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':transportation_type_id', $volunteerProfileData['transportation_type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':service_radius', $volunteerProfileData['service_radius'], PDO::PARAM_INT);
            $stmt->bindParam(':experience', $volunteerProfileData['experience']);
            $stmt->bindParam(':preferred_time_slot_id', $volunteerProfileData['time_slot_id'], PDO::PARAM_INT);
            
            $stmt->execute();
            $volunteerId = $this->db->lastInsertId();
            
            if (!$volunteerId) {
                return false;
            }
            
            // 2. Set volunteer availability
            if (!empty($availabilityData)) {
                $insertQuery = "INSERT INTO volunteer_availability (volunteer_id, day_id) VALUES (:volunteer_id, :day_id)";
                $insertStmt = $this->db->prepare($insertQuery);
                
                foreach ($availabilityData as $dayId) {
                    $insertStmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
                    $insertStmt->bindParam(':day_id', $dayId, PDO::PARAM_INT);
                    $insertStmt->execute();
                }
            }
            
            return $volunteerId;
            
        } catch (PDOException $e) {
            error_log("Error creating volunteer profile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get complete volunteer profile by user ID
     * 
     * @param int $userId User ID
     * @return array|bool Complete volunteer profile data or false if not found
     */
    public function getVolunteerProfile($userId) {
        try {
            // 1. Get the base volunteer profile
            $query = "SELECT * FROM volunteers WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $volunteer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$volunteer) {
                return false;
            }
            
            // 2. Get transportation type details
            if ($volunteer['transportation_type_id']) {
                require_once __DIR__ . '/TransportationTypesModel.php';
                $transportationModel = new TransportationTypesModel($this->db);
                $transportationType = $transportationModel->getTransportationTypeById($volunteer['transportation_type_id']);
                
                if ($transportationType) {
                    $volunteer['transportation_type_name'] = $transportationType['name'];
                }
            }
            
            // 3. Get preferred time slot details
            if ($volunteer['preferred_time_slot_id']) {
                require_once __DIR__ . '/TimeSlotsModel.php';
                $timeSlotsModel = new TimeSlotsModel($this->db);
                $timeSlot = $timeSlotsModel->getTimeSlotById($volunteer['preferred_time_slot_id']);
                
                if ($timeSlot) {
                    $volunteer['time_slot_name'] = $timeSlot['name'];
                }
            }
            
            // 4. Get volunteer availability (days)
            $availabilityQuery = "SELECT va.day_id, d.name as day_name 
                                 FROM volunteer_availability va
                                 JOIN days d ON va.day_id = d.id
                                 WHERE va.volunteer_id = :volunteer_id";
            $availabilityStmt = $this->db->prepare($availabilityQuery);
            $availabilityStmt->bindParam(':volunteer_id', $volunteer['id'], PDO::PARAM_INT);
            $availabilityStmt->execute();
            
            $availability = $availabilityStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Add availability to volunteer profile
            $volunteer['availability'] = $availability;
            
            // Create a simple array of day IDs for easier processing
            $volunteer['available_days'] = array_column($availability, 'day_id');
            
            // Create a simple array of day names for display
            $volunteer['available_day_names'] = array_column($availability, 'day_name');
            
            return $volunteer;
            
        } catch (PDOException $e) {
            error_log("Error getting volunteer profile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get volunteer ID by user ID
     * 
     * @param int $userId User ID
     * @return int|bool Volunteer ID or false if not found
     */
    public function getVolunteerIdByUserId($userId) {
        try {
            $query = "SELECT id FROM volunteers WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['id'] : false;
            
        } catch (PDOException $e) {
            error_log("Error getting volunteer ID by user ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a volunteer profile including all related data
     * 
     * @param int $userId User ID
     * @param array $volunteerProfileData Volunteer profile data (transportation_type_id, service_radius, experience, time_slot_id)
     * @param array $availabilityData Array of day IDs the volunteer is available
     * @return bool True on success, false on failure
     */
    public function updateVolunteerProfile($userId, $volunteerProfileData, $availabilityData) {
        try {
            // Begin transaction
            $this->db->beginTransaction();
            
            // 1. Get volunteer ID from user ID
            $volunteerId = $this->getVolunteerIdByUserId($userId);
            
            if (!$volunteerId) {
                $this->db->rollBack();
                return false;
            }
            
            // 2. Update the base volunteer profile
            $query = "UPDATE volunteers 
                      SET transportation_type_id = :transportation_type_id, 
                          service_radius = :service_radius, 
                          experience = :experience, 
                          preferred_time_slot_id = :preferred_time_slot_id,
                          updated_at = NOW()
                      WHERE id = :volunteer_id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':transportation_type_id', $volunteerProfileData['transportation_type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':service_radius', $volunteerProfileData['service_radius'], PDO::PARAM_INT);
            $stmt->bindParam(':experience', $volunteerProfileData['experience']);
            $stmt->bindParam(':preferred_time_slot_id', $volunteerProfileData['time_slot_id'], PDO::PARAM_INT);
            $stmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // 3. Delete existing volunteer availability
            $deleteQuery = "DELETE FROM volunteer_availability WHERE volunteer_id = :volunteer_id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // 4. Insert new volunteer availability
            if (!empty($availabilityData)) {
                $insertQuery = "INSERT INTO volunteer_availability (volunteer_id, day_id) VALUES (:volunteer_id, :day_id)";
                $insertStmt = $this->db->prepare($insertQuery);
                
                foreach ($availabilityData as $dayId) {
                    $insertStmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
                    $insertStmt->bindParam(':day_id', $dayId, PDO::PARAM_INT);
                    $insertStmt->execute();
                }
            }
            
            // Commit transaction
            $this->db->commit();
            
            return true;
            
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            error_log("Error updating volunteer profile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a volunteer profile by user ID
     * 
     * @param int $userId
     * @return bool True on success, false on failure
     */
    public function deleteVolunteerProfile(int $userId) {
        try {
            $this->db->beginTransaction();
            
            // Get volunteer ID first
            $volunteerId = $this->getVolunteerIdByUserId($userId);
            if (!$volunteerId) {
                $this->db->rollBack();
                return false;
            }
            
            // Delete volunteer availability records first
            $query = "DELETE FROM volunteer_availability WHERE volunteer_id = :volunteer_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Delete the volunteer record
            $query = "DELETE FROM volunteers WHERE id = :volunteer_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':volunteer_id', $volunteerId, PDO::PARAM_INT);
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
            error_log("Error deleting volunteer profile: " . $e->getMessage());
            return false;
        }
    }
}