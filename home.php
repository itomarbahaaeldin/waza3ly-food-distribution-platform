<?php
// Redirect to home page if user is not an admin
if (!$_SESSION['role'] == 'admin') {
    header('Location: /');
    exit;
}

// Initialize database connection
require_once __DIR__ . '/../../config/config.php';
$db = new Config();
$conn = $db->getConnection();

// Load required models
require_once __DIR__ . '/../models/UsersModel.php';
require_once __DIR__ . '/../models/AddressModel.php';
require_once __DIR__ . '/../models/LocationsModel.php';
require_once __DIR__ . '/../models/VolunteersModel.php';
require_once __DIR__ . '/../models/DonorsModel.php';
require_once __DIR__ . '/../models/RequestsModel.php';
require_once __DIR__ . '/../models/RequestItemsModel.php';
require_once __DIR__ . '/../models/PaymentsModel.php';
require_once __DIR__ . '/../models/PaymentMethodsModel.php';
require_once __DIR__ . '/../models/RequestStatusesModel.php';
require_once __DIR__ . '/../models/VolunteerAssignmentModel.php';
require_once __DIR__ . '/../models/RolesModel.php';

// Initialize models
$userModel = new UsersModel($conn);
$addressModel = new AddressModel($conn);
$locationsModel = new LocationsModel($conn);
$volunteerModel = new VolunteerModel($conn);
$donorModel = new DonorsModel($conn);
$requestsModel = new RequestsModel($conn);
$requestItemsModel = new RequestItemsModel($conn);
$paymentsModel = new PaymentsModel($conn);
$paymentMethodsModel = new PaymentMethodsModel($conn);
$requestStatusesModel = new RequestStatusesModel($conn);
$volunteerAssignmentModel = new VolunteerAssignmentModel($conn);
$rolesModel = new RolesModel($conn);

// Get all users with their roles and addresses
$allUsers = $userModel->getAllUsers();

// Loop through all users to add address details
foreach ($allUsers as &$user) {
    if ($user['id']) {
        // Get address details for each user
        $address = $addressModel->getAddressById($user['id']);
        
        if ($address) {
            // Add address details as separate fields in the user object
            $user['street_address'] = $address['street_address'];
            $user['city_name'] = $address['city_name'];
            $user['governorate_name'] = $address['governorate_name'];
            $user['formatted_address'] = $address['street_address'] . ', ' . 
                                         $address['city_name'] . ', ' . 
                                         $address['governorate_name'];
        }
    }
}

// Get all requests with donor and volunteer information
$allRequests = $requestsModel->getAllRequests();

// Get all payments with related information
$allPayments = $paymentsModel->getAllPayments();

// Get all request statuses for filtering
$allStatuses = $requestStatusesModel->getAllStatuses();

// Get all payment methods for filtering
$allPaymentMethods = $paymentMethodsModel->getAllPaymentMethods();

// Get all governorates and cities for filtering
$allGovernorates = $locationsModel->getAllGovernorates();
$allCities = $locationsModel->getAllCities();

// Get all roles
$allRoles = $rolesModel->getAllRoles();

// Calculate dashboard statistics
$stats = [
    'total_users' => count($allUsers),
    'total_donors' => 0,
    'total_volunteers' => 0,
    'total_requests' => count($allRequests),
    'pending_requests' => 0,
    'completed_requests' => 0,
    'in_transit_requests' => 0,
    'accepted_requests' => 0,
    'total_payments' => count($allPayments),
    'payment_amount' => 0
];

// Count user types based on role names
foreach ($allUsers as $user) {
    if ($user['role_id'] === 1) {
        $stats['total_donors']++;
    } elseif ($user['role_id'] === 2) {
        $stats['total_volunteers']++;
    }
}

// Count request statuses
foreach ($allRequests as $request) {
    switch ($request['status_name']) {
        case 'pending':
            $stats['pending_requests']++;
            break;
        case 'accepted':
            $stats['accepted_requests']++;
            break;
        case 'completed':
            $stats['completed_requests']++;
            break;
        case 'in_transit':
            $stats['in_transit_requests']++;
            break;
    }
}

// Calculate total payment amount
foreach ($allPayments as $payment) {
    $stats['payment_amount'] += $payment['amount'];
}

// Function to format date
function formatDate($dateString) {
    if (!$dateString) return 'N/A';
    $date = new DateTime($dateString);
    return $date->format('M d, Y h:i A');
}

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'accepted':
            return 'bg-blue-100 text-blue-800';
        case 'in_transit':
            return 'bg-purple-100 text-purple-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Function to get role badge class
function getRoleBadgeClass($role) {
    switch ($role) {
        case 'donor':
            return 'bg-blue-100 text-blue-800';
        case 'volunteer':
            return 'bg-green-100 text-green-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Waza3ly</title>
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

    .tab-button {
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background-color: #047857;
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 12px;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:hover {
        background-color: #f9fafb;
    }

    .modal {
        transition: opacity 0.25s ease;
    }

    .modal-container {
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Admin Dashboard Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl font-bold text-gray-800">
                            Admin Dashboard
                        </h1>
                        <p class="text-gray-600 mb-2">Welcome, Admin
                        <div
                            class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold mb-4">
                            Administrator
                        </div>
                    </div>
                    <div class="hidden md:block text-right">
                        <div class="text-sm text-gray-500">Last Login</div>
                        <div class="font-medium"><?php echo date('F d, Y h:i A'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Users</div>
                            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_users']; ?></div>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-600"><?php echo $stats['total_donors']; ?> Donors</span>
                        <span class="text-green-600"><?php echo $stats['total_volunteers']; ?> Volunteers</span>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Requests</div>
                            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_requests']; ?></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <span class="text-yellow-600"><?php echo $stats['pending_requests']; ?> Pending</span>
                        <span class="text-blue-600"><?php echo $stats['accepted_requests']; ?> Accepted</span>
                        <span class="text-purple-600"><?php echo $stats['in_transit_requests']; ?> In Transit</span>
                        <span class="text-green-600"><?php echo $stats['completed_requests']; ?> Completed</span>
                    </div>
                </div>

                <!-- Total Payments -->
                <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Payments</div>
                            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_payments']; ?></div>
                        </div>
                    </div>
                    <div class="text-sm text-purple-600">
                        Total Amount: $<?php echo number_format($stats['payment_amount'], 2); ?>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">System Status</div>
                            <div class="text-2xl font-bold text-gray-800">Online</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex overflow-x-auto mb-6 bg-white rounded-lg shadow-sm p-1">
                <button class="tab-button active flex-1 py-3 px-4 text-center font-medium rounded-md"
                    data-tab="users">Users Management</button>
                <button class="tab-button flex-1 py-3 px-4 text-center font-medium rounded-md"
                    data-tab="requests">Requests</button>
                <button class="tab-button flex-1 py-3 px-4 text-center font-medium rounded-md"
                    data-tab="payments">Payments</button>
            </div>

            <!-- Tab Content -->
            <!-- Users Management Tab -->
            <div class="tab-content active" id="users">
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Users Management</h2>
                        <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                            <div class="relative">
                                <input type="text" id="user-search" placeholder="Search users..."
                                    class="w-full md:w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <select id="role-filter"
                                class="w-full md:w-40 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">All Roles</option>
                                <option value="donor">Donor</option>
                                <option value="volunteer">Volunteer</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Location</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <?php foreach ($allUsers as $user): ?>
                                <tr class="user-row"
                                    data-role="<?php echo $rolesModel->getRoleNameById($user['role_id']); ?>"
                                    data-user-id="<?php echo $user['id']; ?>">
                                    <td><?php echo $user['id']; ?></td>
                                    <td class="font-medium">
                                        <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['phone']; ?></td>
                                    <td>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold <?php echo getRoleBadgeClass($rolesModel->getRoleNameById($user['role_id'])); ?>">
                                            <?php echo ucfirst($rolesModel->getRoleNameById($user['role_id'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($user['city_name'] && $user['governorate_name']) {
                                            echo $user['city_name'] . ', ' . $user['governorate_name'];
                                        } else {
                                            echo 'Not Set';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo formatDate($user['created_at']); ?></td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <button
                                                class="view-user-btn bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-md text-xs font-medium transition duration-300"
                                                data-user-id="<?php echo $user['id']; ?>">
                                                View
                                            </button>
                                            <button
                                                class="edit-user-btn bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded-md text-xs font-medium transition duration-300"
                                                data-user-id="<?php echo $user['id']; ?>">
                                                Edit
                                            </button>
                                            <button
                                                class="delete-user-btn bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded-md text-xs font-medium transition duration-300"
                                                data-user-id="<?php echo $user['id']; ?>">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Requests Tab -->
            <div class="tab-content" id="requests">
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">All Requests</h2>
                        <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                            <div class="relative">
                                <input type="text" id="request-search" placeholder="Search requests..."
                                    class="w-full md:w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <select id="status-filter"
                                class="w-full md:w-40 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                <?php foreach ($allStatuses as $status): ?>
                                <option value="<?php echo $status['name']; ?>"><?php echo ucfirst($status['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Donor</th>
                                    <th>Volunteer</th>
                                    <th>Status</th>
                                    <th>Pickup Location</th>
                                    <th>Scheduled Pickup</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="requests-table-body">
                                <?php foreach ($allRequests as $request): ?>
                                <tr class="request-row" data-status="<?php echo $request['status_name']; ?>">
                                    <td><?php echo $request['id']; ?></td>
                                    <td class="font-medium"><?php echo $request['name']; ?></td>
                                    <td><?php echo $request['donor_name'] ?? 'N/A'; ?></td>
                                    <td><?php echo $request['volunteer_name'] ?? 'Not Assigned'; ?></td>
                                    <td>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold <?php echo getStatusBadgeClass($request['status_name']); ?>">
                                            <?php echo ucfirst($request['status_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($request['pickup_city'] && $request['pickup_governorate']) {
                                            echo $request['pickup_city'] . ', ' . $request['pickup_governorate'];
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo formatDate($request['scheduled_pickup']); ?></td>
                                    <td><?php echo formatDate($request['created_at']); ?></td>
                                    <td>
                                        <button
                                            class="view-request-btn bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-md text-xs font-medium transition duration-300"
                                            data-request-id="<?php echo $request['id']; ?>">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payments Tab -->
            <div class="tab-content" id="payments">
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">All Payments</h2>
                        <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                            <div class="relative">
                                <input type="text" id="payment-search" placeholder="Search payments..."
                                    class="w-full md:w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <select id="payment-method-filter"
                                class="w-full md:w-40 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">All Methods</option>
                                <?php foreach ($allPaymentMethods as $method): ?>
                                <option value="<?php echo $method['name']; ?>"><?php echo $method['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Request ID</th>
                                    <th>Request Name</th>
                                    <th>Donor</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Paid At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="payments-table-body">
                                <?php foreach ($allPayments as $payment): ?>
                                <tr class="payment-row"
                                    data-method="<?php echo $payment['payment_method_name'] ?? ''; ?>">
                                    <td><?php echo $payment['id']; ?></td>
                                    <td><?php echo $payment['request_id']; ?></td>
                                    <td class="font-medium"><?php echo $payment['request_name'] ?? 'N/A'; ?></td>
                                    <td><?php echo $payment['donor_name'] ?? 'N/A'; ?></td>
                                    <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <?php echo $payment['payment_method_name'] ?? 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($payment['paid_at']); ?></td>
                                    <td>
                                        <button
                                            class="view-payment-btn bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-md text-xs font-medium transition duration-300"
                                            data-payment-id="<?php echo $payment['id']; ?>">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- User Details Modal -->
    <div id="user-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="modal-container bg-white rounded-lg p-8 max-w-4xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="user-modal-title">User Details</h3>
                <button id="close-user-modal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="user-modal-content">
                <!-- User details will be loaded here via AJAX -->
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-4" id="user-modal-actions">
                <button id="close-user-modal-btn"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300">Close</button>
                <button id="save-user-btn"
                    class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 hidden">Save
                    Changes</button>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div id="request-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="modal-container bg-white rounded-lg p-8 max-w-5xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="request-modal-title">Request Details</h3>
                <button id="close-request-modal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="request-modal-content">
                <!-- Request details will be loaded here via AJAX -->
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button id="close-request-modal-btn"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300">Close</button>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div id="payment-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="modal-container bg-white rounded-lg p-8 max-w-4xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="payment-modal-title">Payment Details</h3>
                <button id="close-payment-modal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="payment-modal-content">
                <!-- Payment details will be loaded here via AJAX -->
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button id="close-payment-modal-btn"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div id="delete-user-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Delete User</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this user? This action cannot be undone and
                will also delete all related data (donations, volunteer assignments, etc.).</p>
            <div class="flex justify-end space-x-4">
                <button id="cancel-delete-user-btn"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300">Cancel</button>
                <button id="confirm-delete-user-btn"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Delete
                    User</button>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Hide all tab contents
                tabContents.forEach(tab => {
                    tab.classList.remove('active');
                });

                // Show the selected tab content
                document.getElementById(tabId).classList.add('active');

                // Update tab buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // User search functionality
        const userSearch = document.getElementById('user-search');
        const roleFilter = document.getElementById('role-filter');
        const userRows = document.querySelectorAll('.user-row');

        function filterUsers() {
            const searchTerm = userSearch.value.toLowerCase();
            const roleValue = roleFilter.value.toLowerCase();

            userRows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const username = row.children[2].textContent.toLowerCase();
                const email = row.children[3].textContent.toLowerCase();
                const phone = row.children[4].textContent.toLowerCase();
                const role = row.getAttribute('data-role').toLowerCase();

                const matchesSearch = name.includes(searchTerm) ||
                    username.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    phone.includes(searchTerm);
                const matchesRole = roleValue === '' || role === roleValue;

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (userSearch) {
            userSearch.addEventListener('input', filterUsers);
        }

        if (roleFilter) {
            roleFilter.addEventListener('change', filterUsers);
        }

        // Request search functionality
        const requestSearch = document.getElementById('request-search');
        const statusFilter = document.getElementById('status-filter');
        const requestRows = document.querySelectorAll('.request-row');

        function filterRequests() {
            const searchTerm = requestSearch.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            requestRows.forEach(row => {
                const id = row.children[0].textContent.toLowerCase();
                const name = row.children[1].textContent.toLowerCase();
                const donor = row.children[2].textContent.toLowerCase();
                const volunteer = row.children[3].textContent.toLowerCase();
                const status = row.getAttribute('data-status').toLowerCase();

                const matchesSearch = id.includes(searchTerm) ||
                    name.includes(searchTerm) ||
                    donor.includes(searchTerm) ||
                    volunteer.includes(searchTerm);
                const matchesStatus = statusValue === '' || status === statusValue;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (requestSearch) {
            requestSearch.addEventListener('input', filterRequests);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterRequests);
        }

        // Payment search functionality
        const paymentSearch = document.getElementById('payment-search');
        const methodFilter = document.getElementById('payment-method-filter');
        const paymentRows = document.querySelectorAll('.payment-row');

        function filterPayments() {
            const searchTerm = paymentSearch.value.toLowerCase();
            const methodValue = methodFilter.value.toLowerCase();

            paymentRows.forEach(row => {
                const id = row.children[0].textContent.toLowerCase();
                const requestId = row.children[1].textContent.toLowerCase();
                const requestName = row.children[2].textContent.toLowerCase();
                const donor = row.children[3].textContent.toLowerCase();
                const method = row.getAttribute('data-method').toLowerCase();

                const matchesSearch = id.includes(searchTerm) ||
                    requestId.includes(searchTerm) ||
                    requestName.includes(searchTerm) ||
                    donor.includes(searchTerm);
                const matchesMethod = methodValue === '' || method === methodValue;

                if (matchesSearch && matchesMethod) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (paymentSearch) {
            paymentSearch.addEventListener('input', filterPayments);
        }

        if (methodFilter) {
            methodFilter.addEventListener('change', filterPayments);
        }

        // Modal functionality
        const userModal = document.getElementById('user-modal');
        const requestModal = document.getElementById('request-modal');
        const paymentModal = document.getElementById('payment-modal');
        const deleteUserModal = document.getElementById('delete-user-modal');

        // User modal handlers
        document.querySelectorAll('.view-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                openUserModal(userId, 'view');
            });
        });

        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                openUserModal(userId, 'edit');
            });
        });

        // Request modal handlers
        document.querySelectorAll('.view-request-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                openRequestModal(requestId);
            });
        });

        // Payment modal handlers
        document.querySelectorAll('.view-payment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-payment-id');
                openPaymentModal(paymentId);
            });
        });

        // Delete user handlers
        let userIdToDelete = null;
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                userIdToDelete = this.getAttribute('data-user-id');
                deleteUserModal.classList.remove('hidden');
            });
        });

        document.getElementById('cancel-delete-user-btn').addEventListener('click', function() {
            deleteUserModal.classList.add('hidden');
            userIdToDelete = null;
        });

        document.getElementById('confirm-delete-user-btn').addEventListener('click', function() {
            if (userIdToDelete) {
                deleteUser(userIdToDelete);
            }
        });

        // Modal functions
        function openUserModal(userId, mode) {
            const userModalContent = document.getElementById('user-modal-content');
            const userModalTitle = document.getElementById('user-modal-title');
            const saveUserBtn = document.getElementById('save-user-btn');

            userModalTitle.textContent = mode === 'edit' ? 'Edit User' : 'User Details';
            saveUserBtn.classList.toggle('hidden', mode !== 'edit');

            userModalContent.innerHTML = `
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                    </div>
                `;

            userModal.classList.remove('hidden');

            fetch(`/api/admin/user/${userId}?mode=${mode}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        userModalContent.innerHTML = data.html;
                    } else {
                        userModalContent.innerHTML =
                            `<p class="text-red-500">${data.message || 'Failed to load user details'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    userModalContent.innerHTML =
                        `<p class="text-red-500">An error occurred while loading user details.</p>`;
                });
        }

        function openRequestModal(requestId) {
            const requestModalContent = document.getElementById('request-modal-content');

            requestModalContent.innerHTML = `
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                    </div>
                `;

            requestModal.classList.remove('hidden');

            fetch(`/api/admin/request/${requestId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        requestModalContent.innerHTML = data.html;
                    } else {
                        requestModalContent.innerHTML =
                            `<p class="text-red-500">${data.message || 'Failed to load request details'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    requestModalContent.innerHTML =
                        `<p class="text-red-500">An error occurred while loading request details.</p>`;
                });
        }

        function openPaymentModal(paymentId) {
            const paymentModalContent = document.getElementById('payment-modal-content');

            paymentModalContent.innerHTML = `
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                    </div>
                `;

            paymentModal.classList.remove('hidden');

            fetch(`/api/admin/payment/${paymentId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        paymentModalContent.innerHTML = data.html;
                    } else {
                        paymentModalContent.innerHTML =
                            `<p class="text-red-500">${data.message || 'Failed to load payment details'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentModalContent.innerHTML =
                        `<p class="text-red-500">An error occurred while loading payment details.</p>`;
                });
        }

        function deleteUser(userId) {
            fetch(`/api/admin/delete-user/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the user row from the table
                        const userRow = document.querySelector(`.user-row[data-user-id="${userId}"]`);
                        if (userRow) {
                            userRow.remove();
                        }
                        alert('User deleted successfully');
                    } else {
                        alert('Failed to delete user: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the user');
                })
                .finally(() => {
                    deleteUserModal.classList.add('hidden');
                    userIdToDelete = null;
                });
        }

        // Close modal handlers
        document.getElementById('close-user-modal').addEventListener('click', () => userModal.classList.add(
            'hidden'));
        document.getElementById('close-user-modal-btn').addEventListener('click', () => userModal.classList.add(
            'hidden'));
        document.getElementById('close-request-modal').addEventListener('click', () => requestModal.classList
            .add('hidden'));
        document.getElementById('close-request-modal-btn').addEventListener('click', () => requestModal
            .classList.add('hidden'));
        document.getElementById('close-payment-modal').addEventListener('click', () => paymentModal.classList
            .add('hidden'));
        document.getElementById('close-payment-modal-btn').addEventListener('click', () => paymentModal
            .classList.add('hidden'));

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === userModal) userModal.classList.add('hidden');
            if (e.target === requestModal) requestModal.classList.add('hidden');
            if (e.target === paymentModal) paymentModal.classList.add('hidden');
            if (e.target === deleteUserModal) {
                deleteUserModal.classList.add('hidden');
                userIdToDelete = null;
            }
        });

        // Save user button handler
        document.getElementById('save-user-btn').addEventListener('click', function() {
            const form = document.getElementById('user-modal-content').querySelector('form');
            if (form) {
                const formData = new FormData(form);

                fetch(form.action, {
                        method: form.method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User updated successfully');
                            userModal.classList.add('hidden');
                            window.location.reload();
                        } else {
                            alert('Failed to update user: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the user');
                    });
            }
        });
    });
    </script>
</body>

</html>