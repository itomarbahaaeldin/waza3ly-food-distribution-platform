<?php
// Redirect to home page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

// Check if user is a donor
if ($_SESSION['role'] !== 'donor') {
    header('Location: /volunteer-dashboard');
    exit;
}

// Initialize database connection
require_once __DIR__ . '/../../config/config.php';
$db = new Config();
$conn = $db->getConnection();

// Load required models
require_once __DIR__ . '/../models/UsersModel.php';
require_once __DIR__ . '/../models/LocationsModel.php';
require_once __DIR__ . '/../models/DonationFrequenciesModel.php';
require_once __DIR__ . '/../models/PickupTimesModel.php';
require_once __DIR__ . '/../models/FoodCategoriesModel.php';
require_once __DIR__ . '/../models/RequestsModel.php';
require_once __DIR__ . '/../models/RequestItemsModel.php';
require_once __DIR__ . '/../models/PickupAddressModel.php';
require_once __DIR__ . '/../models/PaymentMethodsModel.php';
require_once __DIR__ . '/../models/PaymentsModel.php';
require_once __DIR__ . '/../models/PaymentOptionsModel.php';
require_once __DIR__ . '/../models/PaymentMethodOptionsModel.php';
require_once __DIR__ . '/../models/PaymentOptionsValuesModel.php';

// Initialize models
$userModel = new UsersModel($conn);
$locationsModel = new LocationsModel($conn);
$donationFrequenciesModel = new DonationFrequenciesModel($conn);
$pickupTimesModel = new PickupTimesModel($conn);
$foodCategoriesModel = new FoodCategoriesModel($conn);
$requestsModel = new RequestsModel($conn);
$itemsModel = new RequestItemsModel($conn);
$pickupAddressModel = new PickupAddressModel($conn);
$paymentMethodsModel = new PaymentMethodsModel($conn);
$paymentsModel = new PaymentsModel($conn);
$paymentOptionsModel = new PaymentOptionsModel($conn);
$paymentMethodOptionsModel = new PaymentMethodOptionsModel($conn);
$paymentOptionValuesModel = new PaymentOptionValuesModel($conn);

// Get user data
$userId = $_SESSION['user_id'];
$donorId = $_SESSION['donor_id'];
$userData = $userModel->getUserById($userId);

// Get dropdown data
$governorates = $locationsModel->getAllGovernorates();
$donationFrequencies = $donationFrequenciesModel->getAllDonationFrequencies();
$pickupTimes = $pickupTimesModel->getAllPickupTimes();
$foodCategories = $foodCategoriesModel->getAllFoodCategories();
$paymentMethods = $paymentMethodsModel->getAllPaymentMethods();

// Get payment method options for each payment method
$paymentMethodOptions = [];
foreach ($paymentMethods as $method) {
    $options = $paymentMethodOptionsModel->getOptionsByPaymentMethodId($method['id']);
    $paymentMethodOptions[$method['id']] = $options;
}

// Get user's requests
$userRequests = $requestsModel->getRequestsByDonorId($donorId);

// Add item counts to each request
if ($userRequests && is_array($userRequests)) {
    foreach ($userRequests as &$request) {
        $requestItems = $itemsModel->getItemsByRequestId($request['id']);
        $request['items_count'] = is_array($requestItems) ? count($requestItems) : 0;
        $request['payment_amount'] = $request['items_count'] * 0.50;
    }
    unset($request);
}

// Get user's payments
$userPayments = $paymentsModel->getPaymentsByDonorId($donorId);

// Add payment details to each payment
if ($userPayments && is_array($userPayments)) {
    foreach ($userPayments as &$payment) {
        // Get payment method name
        $paymentMethod = $paymentMethodsModel->getPaymentMethodNameById($payment['payment_method_id']);
        $payment['payment_method_name'] = $paymentMethod ?: 'Unknown';
        
        // Get request details
        $request = $requestsModel->getRequestById($payment['request_id']);
        $payment['request_name'] = $request ? $request['name'] : 'Unknown Request';
        
        // Get payment option values
        $payment['option_values'] = $paymentOptionValuesModel->getOptionValuesByPaymentId($payment['id']);
    }
    unset($payment);
}

// Calculate statistics
$totalRequests = is_array($userRequests) ? count($userRequests) : 0;
$pendingRequests = is_array($userRequests) ? count(array_filter($userRequests, fn($r) => $r['status_name']==='pending')) : 0;
$completedRequests = is_array($userRequests) ? count(array_filter($userRequests, function($req) { return $req['status_name'] === 'completed'; })) : 0;
$inTransitRequests = is_array($userRequests) ? count(array_filter($userRequests, function($req) { return $req['status_name'] === 'in_transit'; })) : 0;
$totalPayments = is_array($userPayments) ? count($userPayments) : 0;
$totalAmountPaid = is_array($userPayments) ? array_sum(array_column($userPayments, 'amount')) : 0;

// Handle view details request
$selectedRequest = null;
$selectedRequestItems = [];
if (isset($_GET['view_request']) && is_numeric($_GET['view_request'])) {
    $requestId = (int)$_GET['view_request'];
    foreach ($userRequests as $request) {
        if ($request['id'] == $requestId) {
            $selectedRequest = $request;
            $selectedRequestItems = $itemsModel->getItemsByRequestId($requestId);
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - Waza3ly</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        transform: translateY(-2px);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .tab-button {
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background-color: #047857;
        color: white;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        max-width: 90%;
        max-height: 90%;
        overflow-y: auto;
    }

    .payment-field {
        display: none;
    }

    .payment-field.active {
        display: block;
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Dashboard Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Welcome back, <?php echo htmlspecialchars($userData['first_name']); ?>!
                </h1>
                <p class="text-gray-600 mt-2">
                    Manage your food donations and track your impact.
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex overflow-x-auto mb-6 bg-white rounded-lg shadow-sm p-1">
                <button class="tab-button active flex-1 py-3 px-4 text-center font-medium rounded-md"
                    data-tab="overview">
                    Overview
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-center font-medium rounded-md" data-tab="new-request">
                    New Request
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-center font-medium rounded-md" data-tab="my-requests">
                    Requests History
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-center font-medium rounded-md" data-tab="payments">
                    Payments
                </button>
            </div>

            <!-- Overview Tab -->
            <div class="tab-content active" id="overview">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Total Donations</div>
                                <div class="text-2xl font-bold text-gray-800"><?php echo $totalRequests; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Pending Requests</div>
                                <div class="text-2xl font-bold text-gray-800"><?php echo $pendingRequests; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">In Transit</div>
                                <div class="text-2xl font-bold text-gray-800"><?php echo $inTransitRequests; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Completed</div>
                                <div class="text-2xl font-bold text-gray-800"><?php echo $completedRequests; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Total Paid</div>
                                <div class="text-2xl font-bold text-gray-800">
                                    $<?php echo number_format($totalAmountPaid, 2); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Current Requests</h2>
                        <button onclick="switchTab('my-requests')"
                            class="text-green-600 hover:text-green-500 text-sm font-medium">
                            View All
                        </button>
                    </div>

                    <?php if (empty($userRequests)): ?>
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <p class="text-gray-500">No donation requests yet. Create your first donation request!</p>
                        <button onclick="switchTab('new-request')"
                            class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                            Create Request
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($userRequests, 0, 3) as $request): ?>
                        <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'accepted' => 'bg-blue-100 text-blue-800',
                                'in_transit' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                            $statusClass = $statusColors[$request['status_name']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <p class="font-medium"><?php echo htmlspecialchars($request['name']); ?></p>
                                    <p class="text-sm text-gray-500">
                                        Scheduled:
                                        <?php echo date('M j, Y g:i A', strtotime($request['scheduled_pickup'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?php echo $request['items_count']; ?> items •
                                        $<?php echo number_format($request['payment_amount'], 2); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                    <?php echo ucfirst($request['status_name']); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- New Request Tab -->
            <div class="tab-content" id="new-request">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Create New Donation Request</h2>

                    <form id="donation-request-form" action="/api/requests/create" method="POST">
                        <!-- Request Name and Description Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Request Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Request Name -->
                                <div>
                                    <label for="request_name"
                                        class="block text-sm font-medium text-gray-700 mb-2">Request Name</label>
                                    <input type="text" id="request_name" name="request_name"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                        placeholder="e.g., Weekly Restaurant Surplus, Bakery End-of-Day Items" required>
                                    <p class="text-xs text-gray-500 mt-1">Give your donation request a descriptive name
                                    </p>
                                </div>

                                <!-- Request Description -->
                                <div>
                                    <label for="request_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea id="request_description" name="request_description" rows="4"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input resize-none"
                                        placeholder="Describe your donation request in detail. Include any special instructions, storage requirements, or additional information that would be helpful for volunteers."
                                        required></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Provide details about the donation, special
                                        handling instructions, or any other relevant information</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pickup Information Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Pickup Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Pickup Address -->
                                <div>
                                    <label for="street_address"
                                        class="block text-sm font-medium text-gray-700 mb-2">Pickup Address</label>
                                    <input type="text" id="street_address" name="street_address"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                        placeholder="Enter street address" required>
                                </div>

                                <!-- Governorate -->
                                <div>
                                    <label for="governorate_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">Governorate</label>
                                    <select id="governorate_id" name="governorate_id"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                        required>
                                        <option value="">Select Governorate</option>
                                        <?php foreach ($governorates as $governorate): ?>
                                        <option value="<?php echo $governorate['id']; ?>">
                                            <?php echo htmlspecialchars($governorate['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                    <select id="city_id" name="city_id"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                        required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <!-- Scheduled Pickup -->
                                <div>
                                    <label for="scheduled_pickup"
                                        class="block text-sm font-medium text-gray-700 mb-2">Scheduled Pickup</label>
                                    <input type="datetime-local" id="scheduled_pickup" name="scheduled_pickup"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Food Items Section -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-800">Food Items</h3>
                                <button type="button" id="add-food-item"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300">
                                    Add Item
                                </button>
                            </div>

                            <div id="food-items-container">
                                <!-- Food items will be added here dynamically -->
                            </div>

                            <!-- Payment Calculation -->
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Estimated Payment ($0.50 per
                                        item):</span>
                                    <span id="estimated-payment" class="text-lg font-bold text-green-600">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Payment Information</h3>

                            <!-- Payment Method Selection -->
                            <div class="mb-6">
                                <label for="payment_method_id"
                                    class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select id="payment_method_id" name="payment_method_id"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                    required onchange="updatePaymentFields()">
                                    <option value="">Select Payment Method</option>
                                    <?php foreach ($paymentMethods as $method): ?>
                                    <option value="<?php echo $method['id']; ?>">
                                        <?php echo htmlspecialchars($method['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Dynamic Payment Fields -->
                            <div id="payment-fields">
                                <?php foreach ($paymentMethods as $method): ?>
                                <?php if (!empty($paymentMethodOptions[$method['id']])): ?>
                                <div class="payment-field" data-method="<?php echo $method['id']; ?>">
                                    <h4 class="text-md font-medium text-gray-800 mb-4">
                                        <?php echo htmlspecialchars($method['name']); ?> Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php foreach ($paymentMethodOptions[$method['id']] as $option): ?>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <?php echo ucwords(str_replace('_', ' ', $option['name'])); ?>
                                            </label>
                                            <?php if ($option['name'] === 'expiration_month'): ?>
                                            <select name="payment_options[<?php echo $option['id']; ?>]"
                                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input">
                                                <option value="">Month</option>
                                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?php echo sprintf('%02d', $i); ?>">
                                                    <?php echo sprintf('%02d', $i); ?></option>
                                                <?php endfor;?>
                                            </select>
                                            <?php elseif ($option['name'] === 'expiration_year'): ?>
                                            <select name="payment_options[<?php echo $option['id']; ?>]"
                                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input">
                                                <option value="">Year</option>
                                                <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <?php else: ?>
                                            <input type="text" name="payment_options[<?php echo $option['id']; ?>]"
                                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input"
                                                placeholder="Enter <?php echo str_replace('_', ' ', $option['name']); ?>"
                                                <?php echo ($option['name'] === 'card_number') ? 'maxlength="19"' : ''; ?>
                                                <?php echo ($option['name'] === 'cvv') ? 'maxlength="4"' : ''; ?>>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- For Cash payment method -->
                                <div class="payment-field" data-method="<?php echo $method['id']; ?>">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm text-yellow-800">
                                                <strong><?php echo htmlspecialchars($method['name']); ?>
                                                    Payment:</strong>
                                                Please prepare the exact amount for the volunteer during pickup.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                Create Donation Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Requests History Tab -->
            <div class="tab-content" id="my-requests">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Your Donation Requests</h2>

                    <?php if (empty($userRequests)): ?>
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <p class="text-gray-500 mb-4">No donation requests yet. Create your first donation request!</p>
                        <button onclick="switchTab('new-request')"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                            Create Request
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Request ID</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date Created</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pickup Time</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Items</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($userRequests as $request): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #<?php echo $request['id']; ?>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($request['name'] ?? 'Unnamed Request'); ?>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($request['created_at'])); ?>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('M j, Y g:i A', strtotime($request['scheduled_pickup'])); ?>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'accepted' => 'bg-blue-100 text-blue-800',
                                            'in_transit' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusClass = $statusColors[$request['status_name']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($request['status_name']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $request['items_count']; ?> items
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                        $<?php echo number_format($request['payment_amount'], 2); ?>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?view_request=<?php echo $request['id']; ?>"
                                            class="text-green-600 hover:text-green-900 mr-3">
                                            View Details
                                        </a>
                                        <?php if ($request['status_name'] === 'pending'): ?>
                                        <button onclick="showCancelModal(<?php echo $request['id']; ?>)"
                                            class="text-red-600 hover:text-red-900">
                                            Cancel
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payments Tab -->
            <div class="tab-content" id="payments">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Payment Management</h2>

                    <!-- Payment Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100">Total Payments</p>
                                    <p class="text-2xl font-bold"><?php echo $totalPayments; ?></p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100">Total Amount Paid</p>
                                    <p class="text-2xl font-bold">$<?php echo number_format($totalAmountPaid, 2); ?></p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100">Average Payment</p>
                                    <p class="text-2xl font-bold">
                                        $<?php echo $totalPayments > 0 ? number_format($totalAmountPaid / $totalPayments, 2) : '0.00'; ?>
                                    </p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h3>

                        <?php if (empty($userPayments)): ?>
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            <p class="text-gray-500">No payments made yet.</p>
                        </div>
                        <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Payment ID</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Request</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Method</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($userPayments as $payment): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?php echo $payment['id']; ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($payment['request_name']); ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                            $<?php echo number_format($payment['amount'], 2); ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($payment['payment_method_name']); ?>
                                        </td>
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y g:i A', strtotime($payment['paid_at'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Request Details Modal -->
    <?php if ($selectedRequest): ?>
    <div class="modal active" id="requestDetailsModal">
        <div class="modal-content bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Request Details</h3>
                <a href="?" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Request Information</h4>
                        <p><strong>ID:</strong> #<?php echo $selectedRequest['id']; ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($selectedRequest['name']); ?></p>
                        <p><strong>Status:</strong>
                            <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'accepted' => 'bg-blue-100 text-blue-800',
                                'in_transit' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusClass = $statusColors[$selectedRequest['status_name']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo ucfirst($selectedRequest['status_name']); ?>
                            </span>
                        </p>
                        <p><strong>Created:</strong>
                            <?php echo date('M j, Y g:i A', strtotime($selectedRequest['created_at'])); ?></p>
                        <p><strong>Scheduled Pickup:</strong>
                            <?php echo date('M j, Y g:i A', strtotime($selectedRequest['scheduled_pickup'])); ?></p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Payment Information</h4>
                        <p><strong>Items:</strong> <?php echo $selectedRequest['items_count']; ?> items</p>
                        <p><strong>Amount:</strong> $<?php echo number_format($selectedRequest['payment_amount'], 2); ?>
                        </p>

                        <h4 class="font-medium text-gray-700 mb-2 mt-4">Pickup Address</h4>
                        <p><?php echo htmlspecialchars($pickupAddressModel->formatPickupAddress($selectedRequest['pickup_address_id']) ?? 'N/A'); ?>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-2">Description</h4>
                    <p class="text-gray-600">
                        <?php echo nl2br(htmlspecialchars($selectedRequest['description'] ?? 'No description provided.')); ?>
                    </p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Food Items (<?php echo count($selectedRequestItems); ?>
                        items)</h4>
                    <?php if (!empty($selectedRequestItems)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-50 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Category
                                    </th>
                                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Food
                                        Name</th>
                                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Quantity
                                    </th>
                                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Unit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($selectedRequestItems as $item): ?>
                                <tr>
                                    <td class="py-2 px-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></td>
                                    <td class="py-2 px-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($item['food_name']); ?></td>
                                    <td class="py-2 px-4 text-sm text-gray-900"><?php echo $item['quantity']; ?></td>
                                    <td class="py-2 px-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($item['unit']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-gray-500">No food items found for this request.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Cancel Confirmation Modal -->
    <div class="modal" id="cancelModal">
        <div class="modal-content bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Cancel Request</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to cancel this donation request? This action cannot
                    be undone.</p>

                <form id="cancelForm" action="/api/requests/cancel" method="POST">
                    <input type="hidden" id="cancelRequestId" name="request_id" value="">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideCancelModal()"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                            Yes, Cancel Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
    let foodItemCounter = 0;

    // Food categories data
    const foodCategories = <?php echo json_encode($foodCategories); ?>;

    // Payment methods data
    const paymentMethods = <?php echo json_encode($paymentMethods); ?>;

    // Payment method options mapping
    const paymentMethodOptions = <?php echo json_encode($paymentMethodOptions); ?>;

    // Tab switching functionality
    function switchTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show the selected tab content
        document.getElementById(tabId).classList.add('active');

        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
            if (button.getAttribute('data-tab') === tabId) {
                button.classList.add('active');
            }
        });
    }

    // Cancel modal functions
    function showCancelModal(requestId) {
        document.getElementById('cancelRequestId').value = requestId;
        document.getElementById('cancelModal').classList.add('active');
    }

    function hideCancelModal() {
        document.getElementById('cancelModal').classList.remove('active');
    }

    // Payment field management
    function updatePaymentFields() {
        const selectedMethodId = document.getElementById('payment_method_id').value;

        // Hide all payment fields
        document.querySelectorAll('.payment-field').forEach(field => {
            field.classList.remove('active');
        });

        // Show fields for selected method
        if (selectedMethodId) {
            const methodField = document.querySelector(`.payment-field[data-method="${selectedMethodId}"]`);
            if (methodField) {
                methodField.classList.add('active');
            }
        }
    }

    // Payment calculation
    function updateEstimatedPayment() {
        const foodItems = document.querySelectorAll('.food-item');
        const itemCount = foodItems.length;
        const estimatedAmount = itemCount * 0.50;

        const estimatedPaymentElement = document.getElementById('estimated-payment');
        if (estimatedPaymentElement) {
            estimatedPaymentElement.textContent = `$${estimatedAmount.toFixed(2)}`;
        }
    }

    // Function to handle form submission
    function handleFormSubmit(event) {
        event.preventDefault();

        const form = event.target;
        clearMessages(form);

        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = 'Processing...';

        const formData = new FormData(form);

        fetch(form.action, {
                method: form.method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({
                ...data,
                status: response.status
            })))
            .then(data => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;

                if (data.success) {
                    showSuccessMessage(form, data.message);

                    if (form.id === 'donation-request-form') {
                        form.reset();
                        document.getElementById('food-items-container').innerHTML = '';
                        foodItemCounter = 0;
                        updateEstimatedPayment();
                        updatePaymentFields();
                    }

                    // Reload page after 2 seconds to show new data
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                showFieldError(input, data.errors[field]);
                            } else {
                                showErrorMessage(form, `${field}: ${data.errors[field]}`);
                            }
                        });
                    } else if (data.message) {
                        showErrorMessage(form, data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                showErrorMessage(form, 'An unexpected error occurred. Please try again later.');
            });
    }

    // Function to add food item
    function addFoodItem() {
        foodItemCounter++;
        const container = document.getElementById('food-items-container');

        const foodItemDiv = document.createElement('div');
        foodItemDiv.className = 'food-item border border-gray-200 rounded-lg p-4 mb-4';
        foodItemDiv.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-md font-medium text-gray-800">Food Item ${foodItemCounter}</h4>
                    <button type="button" onclick="removeFoodItem(this)" 
                        class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="food_items[${foodItemCounter}][category_id]" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input" required>
                            <option value="">Select Category</option>
                            ${foodCategories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Food Name</label>
                        <input type="text" name="food_items[${foodItemCounter}][food_name]" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input"
                            placeholder="e.g., Rice, Bread" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" step="0.01" name="food_items[${foodItemCounter}][quantity]" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input"
                            placeholder="0.00" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <input type="text" name="food_items[${foodItemCounter}][unit]" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 form-input"
                            placeholder="e.g., kg, pieces, servings" required>
                    </div>
                </div>
            `;

        container.appendChild(foodItemDiv);
        updateEstimatedPayment();
    }

    // Function to remove food item
    function removeFoodItem(button) {
        button.closest('.food-item').remove();
        updateEstimatedPayment();
    }

    // Function to fetch cities based on governorate
    function fetchCities(governorateId, selectedCityId = null) {
        const citySelect = document.getElementById('city_id');
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;

        if (!governorateId) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = false;
            return;
        }

        fetch(`/api/locations/cities?governorate_id=${governorateId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server returned ${response.status}`);
                }
                return response.json();
            })
            .then(cities => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city.id;
                    opt.textContent = city.name;
                    if (selectedCityId && city.id == selectedCityId) {
                        opt.selected = true;
                    }
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            })
            .catch(err => {
                console.error('Error fetching cities:', err);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
                citySelect.disabled = false;
            });
    }

    // Card number formatting
    function formatCardNumber(input) {
        let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        input.value = formattedValue;
    }

    // Utility functions for messages
    function clearMessages(form) {
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
        });
        form.querySelectorAll('.success-message').forEach(el => el.remove());
    }

    function showFieldError(field, message) {
        field.classList.add('border-red-500');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-xs italic mt-1';
        errorDiv.textContent = message;

        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }

    function showSuccessMessage(form, message) {
        const successDiv = document.createElement('div');
        successDiv.className =
            'success-message bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
        successDiv.innerHTML = `<p>${message}</p>`;
        form.prepend(successDiv);

        successDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function showErrorMessage(form, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className =
            'error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
        errorDiv.innerHTML = `<p>${message}</p>`;
        form.prepend(errorDiv);

        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Tab button event listeners
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
        });

        // Add food item button
        const addFoodItemBtn = document.getElementById('add-food-item');
        if (addFoodItemBtn) {
            addFoodItemBtn.addEventListener('click', addFoodItem);
        }

        // Governorate change handler
        const governorateSelect = document.getElementById('governorate_id');
        if (governorateSelect) {
            governorateSelect.addEventListener('change', function() {
                fetchCities(this.value);
            });
        }

        // Form submission handlers
        const donationForm = document.getElementById('donation-request-form');
        if (donationForm) {
            donationForm.addEventListener('submit', handleFormSubmit);
        }

        const cancelForm = document.getElementById('cancelForm');
        if (cancelForm) {
            cancelForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Failed to cancel request: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while canceling the request.');
                    });
            });
        }

        // Card number formatting
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('card_number')) {
                formatCardNumber(e.target);
            }
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });

        // Add initial food item if on new request tab
        if (document.getElementById('food-items-container')) {
            addFoodItem();
        }
    });
    </script>
</body>

</html>