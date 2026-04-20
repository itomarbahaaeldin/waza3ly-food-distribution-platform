<?php

require_once __DIR__ . '/../models/UsersModel.php';
require_once __DIR__ . '/../models/AddressModel.php';
require_once __DIR__ . '/../models/VolunteersModel.php';

class UserController {
    private $db;
    private $userModel;
    private $addressModel;
    private $volunteerModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new UsersModel($this->db);
        $this->addressModel = new AddressModel($this->db);
        $this->volunteerModel = new VolunteerModel($this->db);
    }

    public function updatePersonalInfo()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Gather incoming
            $userData = [
                'first_name'     => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'last_name'      => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'email'          => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'phone'          => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS),
                'address'        => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS),
                'governorate_id' => filter_input(INPUT_POST, 'governorate_id', FILTER_VALIDATE_INT),
                'city_id'        => filter_input(INPUT_POST, 'city_id', FILTER_VALIDATE_INT),
            ];
            $userId = $_SESSION['user_id'];
    
            // Fetch current
            $currentUser = $this->userModel->getUserById($userId);
            if (!$currentUser) {
                throw new Exception("User not found");
            }
    
            // If nothing changed, short-circuit
            $incoming = [
                'first_name'     => $userData['first_name'],
                'last_name'      => $userData['last_name'],
                'email'          => $userData['email'],
                'phone'          => $userData['phone'],
                'street_address' => $userData['address'],
                'governorate_id' => $userData['governorate_id'],
                'city_id'        => $userData['city_id'],
            ];
            $current = [
                'first_name'     => $currentUser['first_name'],
                'last_name'      => $currentUser['last_name'],
                'email'          => $currentUser['email'],
                'phone'          => $currentUser['phone'],
                'street_address' => $currentUser['street_address'],
                'governorate_id' => $currentUser['governorate_id'],
                'city_id'        => $currentUser['city_id'],
            ];
            if ($incoming === $current) {
                echo json_encode([
                    'success' => true,
                    'message' => 'No changes detected'
                ]);
                return;
            }
    
            // ... existing validator + update logic follows ...
            require_once __DIR__ . '/../utils/BaseValidator.php';
            require_once __DIR__ . '/../utils/PersonalInfoUpdateValidator.php';
            $validator = new PersonalInfoUpdateValidator(new BaseValidator(), $this->db, $currentUser);
            $errors = $validator->validate([
                'first_name'     => $userData['first_name'],
                'last_name'      => $userData['last_name'],
                'email'          => $userData['email'],
                'phone'          => $userData['phone'],
                'address'        => $userData['address'],
                'governorate_id' => $userData['governorate_id'],
                'city_id'        => $userData['city_id'],
            ]);
            
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }

            // Begin transaction
            $this->db->beginTransaction();

            // Update address information
            $addressData = [
                'street_address' => $userData['address'],
                'city_id' => $userData['city_id'],
                'governorate_id' => $userData['governorate_id']
            ];

            $addressUpdated = $this->addressModel->updateAddress($currentUser['address_id'], $addressData);

            if (!$addressUpdated) {
                throw new Exception("Failed to update address information");
            }

            // Update user information
            $userUpdateData = [
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'phone' => $userData['phone']
            ];

            $userUpdated = $this->userModel->updateUser($userId, $userUpdateData);

            if (!$userUpdated) {
                throw new Exception("Failed to update user information");
            }

            // Commit transaction
            $this->db->commit();

            // Update session data
            $_SESSION['user_name'] = $userData['first_name'] . ' ' . $userData['last_name'];

            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Personal information updated successfully'
            ]);

        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log("Update personal info error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update personal information. Please try again later.'
            ]);
        }
    }

    public function updateVolunteerInfo()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
    
        try {
    
            $userId = $_SESSION['user_id'];
    
            // Gather incoming form values
            $volunteerData = [
                'transportation_type_id' => filter_input(INPUT_POST, 'transportation_type_id', FILTER_VALIDATE_INT),
                'service_radius'         => filter_input(INPUT_POST, 'service_radius', FILTER_VALIDATE_INT),
                'experience'             => filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_SPECIAL_CHARS),
                'time_slot_id'           => filter_input(INPUT_POST, 'time_slot_id', FILTER_VALIDATE_INT),
            ];
    
            // Normalize availability array of day IDs
            $availabilityData = isset($_POST['availability']) && is_array($_POST['availability'])
                ? array_map('intval', $_POST['availability'])
                : [];
    
            // Fetch current volunteer profile
            $currentVol = $this->volunteerModel->getVolunteerProfile($userId);
            if (!$currentVol) {
                throw new Exception("Volunteer profile not found");
            }
    
            // Extract existing availability IDs
            $currentAvail = array_column($currentVol['availability'], 'day_id');
    
            // Short-circuit if nothing has changed
            if (
                $volunteerData['transportation_type_id'] === (int)$currentVol['transportation_type_id'] &&
                $volunteerData['service_radius']         === (int)$currentVol['service_radius'] &&
                $volunteerData['experience']             === $currentVol['experience'] &&
                $volunteerData['time_slot_id']           === (int)$currentVol['preferred_time_slot_id'] &&
                empty(array_diff($availabilityData, $currentAvail)) &&
                empty(array_diff($currentAvail, $availabilityData))
            ) {
                echo json_encode([
                    'success' => true,
                    'message' => 'No changes detected'
                ]);
                return;
            }
    
            // Validate
            require_once __DIR__ . '/../utils/BaseValidator.php';
            require_once __DIR__ . '/../utils/VolunteerInfoValidator.php';
            $validator = new VolunteerInfoValidator(new BaseValidator());
            $volunteerData['availability'] = $availabilityData;
            $errors = $validator->validate($volunteerData);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
    
            // Perform update
            $updated = $this->volunteerModel->updateVolunteerProfile(
                $userId,
                $volunteerData,
                $availabilityData
            );
    
            if (!$updated) {
                throw new Exception("Failed to update volunteer profile");
            }
    
            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Volunteer profile updated successfully'
            ]);
    
        } catch (Exception $e) {
            error_log("Update volunteer info error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update volunteer profile. Please try again later.'
            ]);
        }
    }    
    public function updateDonorInfo()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {

            $userId = $_SESSION['user_id'];

            // Gather incoming form values
            $donorData = [
                'donation_frequency_id' => filter_input(INPUT_POST, 'donation_frequency_id', FILTER_VALIDATE_INT),
                'typical_quantity'      => filter_input(INPUT_POST, 'typical_quantity', FILTER_SANITIZE_SPECIAL_CHARS),
                'pickup_time'        => filter_input(INPUT_POST, 'pickup_time_id', FILTER_VALIDATE_INT),
                'additional_info'       => filter_input(INPUT_POST, 'additional_info', FILTER_SANITIZE_SPECIAL_CHARS),
            ];

            // Initialize DonorsModel
            require_once __DIR__ . '/../models/DonorsModel.php';
            $donorModel = new DonorsModel($this->db);

            // Fetch current donor profile
            $currentDonor = $donorModel->getDonorProfile($userId);
            if (!$currentDonor) {
                throw new Exception("Donor profile not found");
            }

            // Short-circuit if nothing has changed
            if (
                $donorData['donation_frequency_id'] === (int)$currentDonor['donation_frequency_id'] &&
                $donorData['typical_quantity'] === $currentDonor['typical_quantity'] &&
                $donorData['pickup_time'] === (int)$currentDonor['pickup_time_id'] &&
                $donorData['additional_info'] === $currentDonor['additional_info']
            ) {
                echo json_encode([
                    'success' => true,
                    'message' => 'No changes detected'
                ]);
                return;
            }

            // Validate
            require_once __DIR__ . '/../utils/BaseValidator.php';
            require_once __DIR__ . '/../utils/DonorInfoValidator.php';
            $validator = new DonorInfoValidator(new BaseValidator());
            $errors = $validator->validate($donorData);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }

            // Perform update
            $updated = $donorModel->updateDonorProfile(
                $userId,
                $donorData
            );

            if (!$updated) {
                throw new Exception("Failed to update donor profile");
            }

            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Donor profile updated successfully'
            ]);

        } catch (Exception $e) {
            error_log("Update donor info error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update donor profile. Please try again later.'
            ]);
        }
    }
    public function deleteAccount()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
    
        try {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['role'];
    
            // 1. Delete role-specific profile first
            if ($userRole === 'volunteer') {
                // Delete volunteer profile
                $profileDeleted = $this->volunteerModel->deleteVolunteerProfile($userId);
                if (!$profileDeleted) {
                    throw new Exception("Failed to delete volunteer profile");
                }
            } elseif ($userRole === 'donor') {
                // Delete donor profile
                require_once __DIR__ . '/../models/DonorsModel.php';
                $donorModel = new DonorsModel($this->db);
                $profileDeleted = $donorModel->deleteDonorProfile($userId);
                if (!$profileDeleted) {
                    throw new Exception("Failed to delete donor profile");
                }
            }
    
            // 2. Delete user's address
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found");
            }
    
            if (isset($user['address_id'])) {
                $addressDeleted = $this->addressModel->deleteAddress($user['address_id']);
                if (!$addressDeleted) {
                    throw new Exception("Failed to delete user address");
                }
            }
    
            // 3. Delete user account
            $userDeleted = $this->userModel->deleteUser($userId);
            if (!$userDeleted) {
                throw new Exception("Failed to delete user account");
            }
    
            // 4. Clear session and log out
            $_SESSION = [];
            session_destroy();
    
            // 5. Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Account deleted successfully',
                'redirect' => '/'
            ]);
    
        } catch (Exception $e) {
            error_log("Delete account error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete account. Please try again later.'
            ]);
        }
    }
}
?>