<?php

// Required model includes
require_once __DIR__ . '/../models/RequestsModel.php';
require_once __DIR__ . '/../models/RequestItemsModel.php';
require_once __DIR__ . '/../models/DonorsModel.php';
require_once __DIR__ . '/../models/PickupAddressModel.php';
require_once __DIR__ . '/../models/FoodCategoriesModel.php';
require_once __DIR__ . '/../models/RequestStatusesModel.php';
require_once __DIR__ . '/../models/VolunteerAssignmentModel.php';
require_once __DIR__ . '/../models/PaymentsModel.php';
require_once __DIR__ . '/../models/PaymentOptionsValuesModel.php';
require_once __DIR__ . '/../models/PaymentMethodsModel.php';
require_once __DIR__ . '/../models/PaymentOptionsModel.php';
require_once __DIR__ . '/../models/PaymentMethodOptionsModel.php';

// Payment strategy includes
require_once __DIR__ . '/../controllers/CashStrategy.php';
require_once __DIR__ . '/../controllers/VisaStrategy.php';
require_once __DIR__ . '/../controllers/FawryStrategy.php';

class RequestController {
    private $db;
    
    private $requestsModel;
    private $donorsModel;
    private $pickupAddressModel;
    private $foodCategoriesModel;
    private $itemsModel;
    private $requestStatusesModel;
    private $volunteerAssignmentModel;
    private $paymentsModel;
    private $valuesModel;
    private $methodsModel;
    private $optionsModel;

    public function __construct($db) {
        $this->db = $db;
        
        // Initialize all model instances
        $this->requestsModel = new RequestsModel($this->db);
        $this->donorsModel = new DonorsModel($this->db);
        $this->pickupAddressModel = new PickupAddressModel($this->db);
        $this->foodCategoriesModel = new FoodCategoriesModel($this->db);
        $this->itemsModel = new RequestItemsModel($this->db);
        $this->requestStatusesModel = new RequestStatusesModel($this->db);
        $this->volunteerAssignmentModel = new VolunteerAssignmentModel($this->db);
        $this->paymentsModel = new PaymentsModel($this->db);
        $this->valuesModel = new PaymentOptionValuesModel($this->db);
        $this->methodsModel = new PaymentMethodsModel($this->db);
        $this->optionsModel = new PaymentOptionsModel($this->db);
    }

    public function createRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            $donorId = $_SESSION['donor_id'];

            // 1. Enforce max 3 open requests
            $allRequests = $this->requestsModel->getRequestsByDonorId($donorId);
            $open = 0;
            foreach ($allRequests as $r) {
                if (in_array($r['status_name'], ['pending','accepted','in_transit'], true)) {
                    $open++;
                }
            }
            if ($open >= 3) {
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => 'You already have 3 active requests.'
                ]);
                return;
            }

            // 2. Sanitize request inputs
            $name            = filter_input(INPUT_POST, 'request_name', FILTER_SANITIZE_SPECIAL_CHARS);
            $description     = filter_input(INPUT_POST, 'request_description', FILTER_SANITIZE_SPECIAL_CHARS);
            $street          = filter_input(INPUT_POST, 'street_address', FILTER_SANITIZE_SPECIAL_CHARS);
            $governorateId   = filter_input(INPUT_POST, 'governorate_id', FILTER_VALIDATE_INT);
            $cityId          = filter_input(INPUT_POST, 'city_id', FILTER_VALIDATE_INT);
            $scheduledPickup = filter_input(INPUT_POST, 'scheduled_pickup', FILTER_SANITIZE_SPECIAL_CHARS);

            // 3. Build food items array
            $foodItems = [];
            foreach ($_POST['food_items'] ?? [] as $item) {
                $foodItems[] = [
                    'category_id' => (int)$item['category_id'],
                    'food_name'   => htmlspecialchars($item['food_name']),
                    'quantity'    => (float)$item['quantity'],
                    'unit'        => htmlspecialchars($item['unit']),
                ];
            }

            // 4. Create pickup address
            $pickupAddressId = $this->pickupAddressModel->createPickupAddress([
                'street_address' => $street,
                'city_id'        => $cityId,
                'governorate_id' => $governorateId
            ]);
            if (!$pickupAddressId) {
                throw new Exception("Failed to create address");
            }

            // 5. Insert request
            $pendingId = $this->requestStatusesModel->getStatusIdByName('pending');
            $requestId = $this->requestsModel->createRequest(
                $donorId,
                $pickupAddressId,
                $pendingId,
                $name,
                $description,
                $scheduledPickup
            );
            if (!$requestId) {
                throw new Exception("Failed to create request");
            }

            // 6. Insert items
            foreach ($foodItems as $i) {
                if (!$this->itemsModel->createItem(
                    $requestId,
                    $i['category_id'],
                    $i['food_name'],
                    $i['quantity'],
                    $i['unit']
                )) {
                    throw new Exception("Failed to add item");
                }
            }

            // payment_method_id comes from your form
            $methodId = (int)$_POST['payment_method_id'];

            // Load correct strategy based on payment method
            switch ($this->methodsModel->getPaymentMethodNameById($methodId)) {
                case 'VISA':
                    $visaOptions = [
                        'card_number' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('card_number')] ?? '',
                        'expiration_month' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('expiration_month')] ?? '',
                        'expiration_year' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('expiration_year')] ?? '',
                        'cvv' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('cvv')] ?? '',
                    ];
                    $strategy = new VisaStrategy();  
                    $result = $strategy->process($visaOptions); 
                    break;
                
                case 'FAWRY':
                    $fawryOptions = [
                        'holder_name' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('holder_name')] ?? '',
                        'reference_number' => $_POST['payment_options'][$this->optionsModel->getPaymentOptionIdbyName('reference_number')] ?? ''
                    ];
                    $strategy = new FawryStrategy();  
                    $result = $strategy->process($fawryOptions);  
                    break;
                
                case 'CASH':
                default:
                    $strategy = new CashStrategy(); 
                    $result = $strategy->process([]);  
                    break;
            }

            // Validate payment data using the chosen strategy
            if (is_array($result) && $result) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors'  => $result
                ]);
                return;
            }

            // Calculate amount (can change based on your business logic)
            $amount = count($foodItems) * 0.5; 

            // Create payment
            $paymentId = $this->paymentsModel->createPayment($donorId, $requestId, $methodId, $amount);
            if (!$paymentId) {
                throw new Exception("Failed to create payment");
            }

            // Save the payment option values to the database
            foreach ($_POST['payment_options'] as $optId => $val) {
                if (!empty($optId) && !empty($val)) {
                    $sanitizedValue = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                    $this->valuesModel->createPaymentOptionValue($paymentId, (int)$optId, $sanitizedValue);
                } else {
                    error_log("Invalid payment option value detected: Option ID: $optId, Value: $val");
                }
            }

            echo json_encode([
                'success'    => true,
                'message'    => 'Donation request created and paid!',
                'request_id' => $requestId,
                'payment_id' => $paymentId
            ]);

        } catch (Exception $e) {
            error_log("createRequest error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function cancelRequest() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $userId = $_SESSION['user_id'];
            $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
            
            if (!$requestId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
                return;
            }
            
            // Get request details
            $request = $this->requestsModel->getRequestById($requestId);

            // Delete associated items - fixed variable name
            $this->itemsModel->deleteItemsByRequestId($requestId);

            if (!$request) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }
            
            // Delete the request
            $deleted = $this->requestsModel->deleteRequest($requestId);
            
            if (!$deleted) {
                throw new Exception("Failed to cancel donation request");
            }
            
            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Donation request cancelled successfully'
            ]);
            
        } catch (Exception $e) {
            error_log("Cancel request error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to cancel donation request. Please try again later.'
            ]);
        }
    }
    
    public function acceptRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
    
        try {
            $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
            $volunteerId = $_SESSION['volunteer_id'];
    
            if (!$requestId || !$volunteerId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid request or volunteer']);
                return;
            }
    
            // Check if the request exists and is in 'pending' status
            $request = $this->requestsModel->getRequestById($requestId);
            if (!$request) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }
    
            if ($request['status_name'] !== 'pending') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Request cannot be accepted']);
                return;
            }
    
            // Check if the volunteer already has 2 accepted or in_transit requests
            $assignedRequests = $this->volunteerAssignmentModel->getAssignmentsByVolunteerId($volunteerId);
    
            $activeRequestsCount = 0;
            foreach ($assignedRequests as $assignedRequest) {
                if (in_array($assignedRequest['status_name'], ['accepted', 'in_transit'])) {
                    $activeRequestsCount++;
                }
            }
    
            if ($activeRequestsCount >= 2) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'You cannot accept more than 2 requests at a time']);
                return;
            }
    
            // Assign the request to the volunteer
            $assignmentId = $this->volunteerAssignmentModel->createAssignment($volunteerId, $requestId);
            if (!$assignmentId) {
                throw new Exception("Failed to assign volunteer to request");
            }
    
            // Update request status to 'accepted'
            $acceptedStatusId = $this->requestStatusesModel->getStatusIdByName('accepted');
            if (!$acceptedStatusId || !$this->requestsModel->updateRequestStatus($requestId, $acceptedStatusId)) {
                throw new Exception("Failed to update request status to accepted");
            }
    
            // Return success response
            echo json_encode(['success' => true, 'message' => 'Request accepted successfully']);
        } catch (Exception $e) {
            error_log("Error accepting request: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Could not accept request. Please try again.']);
        }
    }    
    
    public function completePickup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
    
        try {
            $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
            $volunteerId = $_SESSION['volunteer_id'];
    
            if (!$requestId || !$volunteerId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid request or volunteer']);
                return;
            }
    
            // Check if the request exists and is in 'accepted' status
            $request = $this->requestsModel->getRequestById($requestId);
            if (!$request) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }
    
            if ($request['status_name'] !== 'accepted') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Request cannot be marked as in transit']);
                return;
            }
    
            // Update request status to 'in_transit'
            $inTransitStatusId = $this->requestStatusesModel->getStatusIdByName('in_transit');
            if (!$inTransitStatusId || !$this->requestsModel->updateRequestStatus($requestId, $inTransitStatusId)) {
                throw new Exception("Failed to update request status to in transit");
            }
    
            // Return success response
            echo json_encode(['success' => true, 'message' => 'Request marked as in transit successfully']);
        } catch (Exception $e) {
            error_log("Error completing pickup: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Could not complete pickup. Please try again.']);
        }
    }    

    public function completeRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
    
        try {
            $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
            $volunteerId = $_SESSION['volunteer_id'];
    
            if (!$requestId || !$volunteerId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid request or volunteer']);
                return;
            }
    
            // Check if the request exists and is in 'in_transit' status
            $request = $this->requestsModel->getRequestById($requestId);
            if (!$request) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }
    
            if ($request['status_name'] !== 'in_transit') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Request cannot be marked as completed']);
                return;
            }
    
            // Update request status to 'completed'
            $completedStatusId = $this->requestStatusesModel->getStatusIdByName('completed');
            if (!$completedStatusId || !$this->requestsModel->updateRequestStatus($requestId, $completedStatusId)) {
                throw new Exception("Failed to update request status to completed");
            }
    
            // Return success response
            echo json_encode(['success' => true, 'message' => 'Request marked as completed successfully']);
        } catch (Exception $e) {
            error_log("Error completing request: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Could not complete request. Please try again.']);
        }
    }    
}
?>