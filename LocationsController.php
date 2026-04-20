<?php
require_once __DIR__ . '/../models/UsersModel.php';
require_once __DIR__ . '/../models/VolunteersModel.php';
require_once __DIR__ . '/../models/DaysModel.php';
require_once __DIR__ . '/../models/TimeSlotsModel.php';
require_once __DIR__ . '/../models/TransportationTypesModel.php';
require_once __DIR__ . '/../models/AddressModel.php';
require_once __DIR__ . '/../models/RolesModel.php';
require_once __DIR__ . '/../models/DonorsModel.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../utils/ValidatorInterface.php';
require_once __DIR__ . '/../utils/BaseValidator.php';
require_once __DIR__ . '/../utils/ValidatorDecorator.php';
require_once __DIR__ . '/../utils/PersonalInfoValidator.php';
require_once __DIR__ . '/../utils/AddressInfoValidator.php';
require_once __DIR__ . '/../utils/AccountInfoValidator.php';
require_once __DIR__ . '/../utils/VolunteerInfoValidator.php';
require_once __DIR__ . '/../utils/PasswordChangeValidator.php';

class AuthController {
    private $db;
    private $userModel;
    private $volunteerModel;
    private $daysModel;
    private $timeSlotsModel;
    private $transportationTypesModel;
    private $addressModel;
    private $roleModel;
    private $validator;
    private $donorModel;
    private $adminModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new UsersModel($db);
        $this->volunteerModel = new VolunteerModel($db);
        $this->daysModel = new DaysModel($db);
        $this->timeSlotsModel = new TimeSlotsModel($db);
        $this->transportationTypesModel = new TransportationTypesModel($db);
        $this->addressModel = new AddressModel($db);
        $this->roleModel = new RolesModel($db);
        $this->donorModel = new DonorsModel($db);
        $this->adminModel = new AdminModel($db);
    }
    
    public function registerVolunteer() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Get and sanitize POST data
            $formData = [
                'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS),
                'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS),
                'governorate_id' => filter_input(INPUT_POST, 'governorate_id', FILTER_VALIDATE_INT),
                'city_id' => filter_input(INPUT_POST, 'city_id', FILTER_VALIDATE_INT),
                'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'transportation_type_id' => filter_input(INPUT_POST, 'transportation_type_id', FILTER_VALIDATE_INT),
                'service_radius' => filter_input(INPUT_POST, 'service_radius', FILTER_VALIDATE_INT),
                'experience' => filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_SPECIAL_CHARS),
                'availability' => isset($_POST['availability']) ? $_POST['availability'] : [],
                'time_slot_id' => filter_input(INPUT_POST, 'time_slot_id', FILTER_VALIDATE_INT)
            ];

            // Build decorator chain for registration:
            $validator = new VolunteerInfoValidator(
                new AccountInfoValidator(
                    new AddressInfoValidator(
                        new PersonalInfoValidator(
                            new BaseValidator(),
                            $this->db
                        )
                    )
                )
            );
        
            // Run validation:
            $errors = $validator->validate($formData);
            
            if (!empty($errors)) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // 1. Create address record
            $addressData = [
                'street_address' => $formData['address'],
                'city_id' => $formData['city_id'],
                'governorate_id' => $formData['governorate_id']
            ];
            
            $addressId = $this->addressModel->createAddress($addressData);
            
            if (!$addressId) {
                throw new Exception("Failed to create address record");
            }
            
            // 2. Get role ID for volunteer
            $roleId = $this->roleModel->getRoleIdByName('volunteer');
            
            if (!$roleId) {
                throw new Exception("Volunteer role not found");
            }
            
            // 3. Create user record
            $userData = [
                'username' => $formData['username'],
                'password' => $_POST['password'],
                'email' => $formData['email'],
                'first_name' => $formData['first_name'],
                'last_name' => $formData['last_name'],
                'phone' => $formData['phone'],
                'address_id' => $addressId,
                'role_id' => $roleId,
            ];
            
            $userId = $this->userModel->createUser($userData);
            
            if (!$userId) {
                throw new Exception("Failed to create user account");
            }
            
            // 4. Create volunteer profile - now using IDs directly from the form
            $volunteerProfileData = [
                'user_id' => $userId,
                'transportation_type_id' => $formData['transportation_type_id'],
                'service_radius' => $formData['service_radius'],
                'experience' => $formData['experience'],
                'time_slot_id' => $formData['time_slot_id'],
            ];
            
            // The availability array now contains day IDs directly, no need for conversion
            $volunteerId = $this->volunteerModel->createVolunteerProfile($volunteerProfileData, $formData['availability']);
            
            if (!$volunteerId) {
                throw new Exception("Failed to create volunteer profile");
            }
            
            // Commit transaction
            $this->db->commit();
            
            // Start session and set user data
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $formData['username'];
            $_SESSION['role'] = 'volunteer';
            $_SESSION['volunteer_id'] = $volunteerId;
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Registration successful! Welcome to our volunteer network.',
                'redirect' => '/'
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("Volunteer registration error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again later.']);
        }
    }
    
    public function registerDonor() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Get and sanitize POST data
            $formData = [
                'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS),
                'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS),
                'city_id' => filter_input(INPUT_POST, 'city_id', FILTER_VALIDATE_INT),
                'governorate_id' => filter_input(INPUT_POST, 'governorate_id', FILTER_VALIDATE_INT),
                'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'donation_frequency_id' => filter_input(INPUT_POST, 'donation_frequency_id', FILTER_VALIDATE_INT),
                'typical_quantity' => filter_input(INPUT_POST, 'typical_quantity', FILTER_VALIDATE_INT),
                'pickup_time' => filter_input(INPUT_POST, 'pickup_time', FILTER_VALIDATE_INT),
                'additional_info' => filter_input(INPUT_POST, 'additional_info', FILTER_SANITIZE_SPECIAL_CHARS)
            ];

            // Load required models for donor registration
            require_once __DIR__ . '/../models/DonorsModel.php';
            $donorsModel = new DonorsModel($this->db);

            // Build decorator chain for registration:
            require_once __DIR__ . '/../utils/DonorInfoValidator.php';
            $validator = new DonorInfoValidator(
                new AccountInfoValidator(
                    new AddressInfoValidator(
                        new PersonalInfoValidator(
                            new BaseValidator(),
                            $this->db
                        )
                    )
                )
            );
        
            // Run validation:
            $errors = $validator->validate($formData);
            
            if (!empty($errors)) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // 1. Create address record
            $addressData = [
                'street_address' => $formData['address'],
                'city_id' => $formData['city_id'],
                'governorate_id' => $formData['governorate_id'] 
            ];
            
            $addressId = $this->addressModel->createAddress($addressData);
            
            if (!$addressId) {
                throw new Exception("Failed to create address record");
            }
            
            // 2. Get role ID for donor
            $roleId = $this->roleModel->getRoleIdByName('donor');
            
            if (!$roleId) {
                throw new Exception("Donor role not found");
            }
            
            // 3. Create user record
            $userData = [
                'username' => $formData['username'],
                'password' => $formData['password'],
                'email' => $formData['email'],
                'first_name' => $formData['first_name'],
                'last_name' => $formData['last_name'],
                'phone' => $formData['phone'],
                'address_id' => $addressId,
                'role_id' => $roleId,
            ];
            
            $userId = $this->userModel->createUser($userData);
            
            if (!$userId) {
                throw new Exception("Failed to create user account");
            }
            
            // 4. Create donor profile
            $donorProfileData = [
                'user_id' => $userId,
                'donation_frequency_id' => $formData['donation_frequency_id'],
                'typical_quantity' => $formData['typical_quantity'],
                'pickup_time_id' => $formData['pickup_time'],
                'additional_info' => $formData['additional_info'] ?? null
            ];
            
            $donorId = $donorsModel->createDonorProfile($donorProfileData);
            
            if (!$donorId) {
                throw new Exception("Failed to create donor profile");
            }
            
            // Commit transaction
            $this->db->commit();
            
            // Start session and set user data
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $formData['username'];
            $_SESSION['role'] = 'donor';
            $_SESSION['donor_id'] = $donorId;
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Registration successful! Welcome to our donor network.',
                'redirect' => '/'
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("Donor registration error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again later.']);
        }
    }

    public function loginUser() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); 
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Get and sanitize POST data
            $identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'] ?? '';
            $password = trim($_POST['password'] ?? '');
            
            // Check if the identifier is for an admin first
            $admin = $this->adminModel->findAdminByEmail($identifier);
            
            if ($admin) {
                $passwordVerified = $this->adminModel->verifyPassword($password, $admin['password']);
                
                if (!$passwordVerified) {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'message' => 'Invalid password']);
                    return;
                }
                
                $_SESSION['role'] = 'admin';
                
                // Return success response
                echo json_encode([
                    'success' => true, 
                    'message' => 'Admin login successful!',
                    'redirect' => '/admin-dashboard'
                ]);
                return;
            }
            
            // If not an admin, proceed with user login
            $user = $this->userModel->findUserByEmailOrUsername($identifier);
            
            if (!$user) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid email']);
                return;
            }
            
            // Verify the password
            $passwordVerified = $this->userModel->verifyPassword($password, $user['password']);
            
            if (!$passwordVerified) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid password']);
                return;
            }
            
            // Start session and set user data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Get user role
            $roleModel = new RolesModel($this->db);
            $role = $roleModel->getRoleById($user['role_id']);
            $_SESSION['role'] = $role['name'];
            
            // If user is a volunteer, get volunteer ID
            if ($role['name'] === 'volunteer') {
                $volunteerId = $this->volunteerModel->getVolunteerIdByUserId($user['id']);
                if ($volunteerId) {
                    $_SESSION['volunteer_id'] = $volunteerId;
                }
            }
            
            // If user is a donor, get donor ID
            if ($role['name'] === 'donor') {
                $donorId = $this->donorModel->getDonorIdByUserId($user['id']);
                if ($donorId) {
                    $_SESSION['donor_id'] = $donorId;
                }
            }           
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful!',
                'redirect' => '/'
            ]);
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Login failed. Please try again later.']);
        }
    }
    
    public function logoutUser()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // If there's no user logged in, just redirect home
        if (empty($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    
        // Unset all session variables
        $_SESSION = [];
    
        // Destroy the session
        session_destroy();
    
        // Redirect to home page
        header('Location: /');
        exit;
    }    

    public function changePassword()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Get and sanitize POST data
            $passwordData = [
                'current_password' => $_POST['current_password'] ?? '',
                'new_password' => $_POST['new_password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            // Get user from database
            $userId = $_SESSION['user_id'];
            $user = $this->userModel->getUserById($userId);
            
            if (!$user) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found']);
                return;
            }
            
            // Validate password data using the decorator chain
            $validator = new PasswordChangeValidator(
                new BaseValidator(),
                $user['password']
            );
            $errors = $validator->validate($passwordData);

            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Update password
            $updateData = ['password' => $passwordData['new_password']];
            $updated = $this->userModel->updateUser($userId, $updateData);
            
            if (!$updated) {
                throw new Exception("Failed to update password");
            }
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Password changed successfully'
            ]);
            
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to change password. Please try again later.']);
        }
    }
    public function resetPassword()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Get and sanitize POST data
            $passwordData = [
                'identifier'       => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS),
                'new_password'     => $_POST['new_password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            // Find the user by email or username
            $user = $this->userModel->findUserByEmailOrUsername($passwordData['identifier']);
            
            if (!$user) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found']);
                return;
            }
            
            // Validate password data using a password reset validator
            require_once __DIR__ . '/../utils/BaseValidator.php';
            require_once __DIR__ . '/../utils/PasswordResetValidator.php';
            $validator = new PasswordResetValidator(
                new BaseValidator(),
                $user['password']
            );
            $errors = $validator->validate($passwordData);
    
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Update password
            $updateData = ['password' => $passwordData['new_password']];
            $updated = $this->userModel->updateUser($user['id'], $updateData);
            
            if (!$updated) {
                throw new Exception("Failed to reset password");
            }
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Password reset successfully'
            ]);
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to reset password. Please try again later.']);
        }
    }    
}
?>