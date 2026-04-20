<?php
// Redirect to home page if user is not logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

// Include necessary models and initialize database connection
require_once __DIR__ . '/../models/DonationFrequenciesModel.php';
require_once __DIR__ . '/../models/PickupTimesModel.php';
require_once __DIR__ . '/../models/LocationsModel.php';

// Initialize database connection
$config = new Config();
$db = $config->getConnection();

// Initialize models
$donationFrequenciesModel = new DonationFrequenciesModel($db);
$pickupTimesModel = new PickupTimesModel($db);
$locationsModel = new LocationsModel($db);

// Fetch data from models
$donationFrequencies = $donationFrequenciesModel->getAllDonationFrequencies();
$pickupTimes = $pickupTimesModel->getAllPickupTimes();
$governorates = $locationsModel->getAllGovernorates();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration - Waza3ly</title>
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

    .step {
        display: none;
    }

    .step.active {
        display: block;
    }

    .step-indicator {
        transition: all 0.3s ease;
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-green-800 mb-4">Donor Registration</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Register as a food donor to help reduce waste and feed those
                    in need. Your surplus food can make a real difference in someone's life.</p>
            </div>

            <!-- Step Indicators -->
            <div class="flex justify-center mb-12">
                <div class="flex items-center">
                    <div class="step-indicator bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold"
                        id="step-indicator-1">1</div>
                    <div class="h-1 w-12 bg-green-200" id="step-line-1"></div>
                    <div class="step-indicator bg-green-200 text-green-700 w-10 h-10 rounded-full flex items-center justify-center font-bold"
                        id="step-indicator-2">2</div>
                    <div class="h-1 w-12 bg-green-200" id="step-line-2"></div>
                    <div class="step-indicator bg-green-200 text-green-700 w-10 h-10 rounded-full flex items-center justify-center font-bold"
                        id="step-indicator-3">3</div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-xl shadow-md p-8">
                <form id="donor-form" action="/api/register/donor" method="POST">
                    <!-- Step 1: Personal Information -->
                    <div class="step active" id="step-1">
                        <h2 class="text-2xl font-semibold text-green-700 mb-6">Personal Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First
                                    Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                    placeholder="Your first name" required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last
                                    Name</label>
                                <input type="text" id="last_name" name="last_name"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                    placeholder="Your last name" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                Address</label>
                            <input type="email" id="email" name="email"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Your email address" required>
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone
                                Number</label>
                            <input type="tel" id="phone" name="phone"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Your phone number" required>
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street
                                Address</label>
                            <input type="text" id="address" name="address"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Your street address" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="governorate"
                                    class="block text-sm font-medium text-gray-700 mb-2">Governorate</label>
                                <select id="governorate" name="governorate_id"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                    required>
                                    <option value="">Select Governorate</option>
                                    <?php foreach ($governorates as $governorate): ?>
                                    <option value="<?= $governorate['id'] ?>">
                                        <?= htmlspecialchars($governorate['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700 mb-2">City/District</label>
                                <select id="city" name="city_id"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                    required>
                                    <option value="">Select City/District</option>
                                    <!-- City options will be populated dynamically based on selected governorate -->
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button"
                                class="bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                onclick="nextStep(1)">Next Step</button>
                        </div>
                    </div>

                    <!-- Step 2: Donation Information -->
                    <div class="step" id="step-2">
                        <h2 class="text-2xl font-semibold text-green-700 mb-6">Donation Information</h2>

                        <div class="mb-6">
                            <label for="donation_frequency" class="block text-sm font-medium text-gray-700 mb-2">How
                                often do you expect to have food available for donation?</label>
                            <select id="donation_frequency" name="donation_frequency_id"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                required>
                                <option value="">Select frequency</option>
                                <?php foreach ($donationFrequencies as $frequency): ?>
                                <option value="<?= $frequency['id'] ?>"><?= htmlspecialchars($frequency['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="typical_quantity" class="block text-sm font-medium text-gray-700 mb-2">Typical
                                Quantity (approximate servings)</label>
                            <input type="number" id="typical_quantity" name="typical_quantity"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Approximate number of servings" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Pickup
                                Time</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php foreach ($pickupTimes as $time): ?>
                                <div class="flex items-center">
                                    <input type="radio" id="pickup_time_<?= $time['id'] ?>" name="pickup_time"
                                        value="<?= $time['id'] ?>"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" required>
                                    <label for="pickup_time_<?= $time['id'] ?>"
                                        class="ml-2 block text-sm text-gray-700">
                                        <?= htmlspecialchars($time['name']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Additional
                                Information (Optional)</label>
                            <textarea id="additional_info" name="additional_info" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Any additional information about your food donations"></textarea>
                        </div>

                        <div class="flex justify-between">
                            <button type="button"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-300"
                                onclick="prevStep(2)">Previous</button>
                            <button type="button"
                                class="bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                onclick="nextStep(2)">Next Step</button>
                        </div>
                    </div>

                    <!-- Step 3: Account Information -->
                    <div class="step" id="step-3">
                        <h2 class="text-2xl font-semibold text-green-700 mb-6">Account Information</h2>

                        <div class="mb-6">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" id="username" name="username"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Choose a username" required>
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Choose a secure password" required>
                            <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long and
                                include a mix of letters, numbers, and special characters.</p>
                        </div>

                        <div class="mb-6">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                                Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Confirm your password" required>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">I agree to the <a href="/terms"
                                            class="text-green-600 hover:text-green-500">Terms of Service</a> and <a
                                            href="/privacy" class="text-green-600 hover:text-green-500">Privacy
                                            Policy</a></label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-300"
                                onclick="prevStep(3)">Previous</button>
                            <button type="submit"
                                class="bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Complete
                                Registration</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Additional Information -->
            <div class="mt-12 bg-green-50 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-green-800 mb-4">What Happens Next?</h3>
                <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                    <li>We'll review your registration and activate your account within 1 business day.</li>
                    <li>You'll receive an email with login details and instructions for creating food donation listings.
                    </li>
                    <li>When you have food to donate, simply create a listing with details about the food, quantity, and
                        pickup time.</li>
                    <li>Nearby volunteers will be notified and can accept the task to pick up and distribute your
                        donation.</li>
                </ol>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const donorForm = document.getElementById('donor-form');

        // Set up governorate-city relationship
        const governorateSelect = document.getElementById('governorate');
        const citySelect = document.getElementById('city');

        if (governorateSelect && citySelect) {
            governorateSelect.addEventListener('change', function() {
                const governorateId = this.value;

                // Clear existing options
                citySelect.innerHTML = '<option value="">Select City/District</option>';

                if (governorateId) {
                    // Show loading indicator
                    citySelect.disabled = true;

                    // Fetch cities from the server
                    fetch(
                            `/api/locations/cities?governorate_id=${governorateId}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            }
                        )
                        .then(response => response.json())
                        .then(cities => {
                            // Add city options
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });

                            // Enable select
                            citySelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            citySelect.disabled = false;
                        });
                }
            });
        }

        // Function to handle form submission
        if (donorForm) {
            donorForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous error messages and reset field borders
                clearAllErrors();

                // Show loading indicator
                const submitButton = donorForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Processing...';

                // Get form data
                const formData = new FormData(donorForm);

                // Send AJAX request
                fetch(donorForm.action, {
                        method: donorForm.method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json().then(data => ({
                        ok: response.ok,
                        status: response.status,
                        data: data
                    })))
                    .then(result => {
                        // Reset button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;

                        if (result.ok && result.data.success) {
                            // Show success message
                            const successMessage = document.createElement('div');
                            successMessage.className =
                                'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                            successMessage.innerHTML = `<p>${result.data.message}</p>`;
                            donorForm.prepend(successMessage);

                            // Redirect after a short delay
                            if (result.data.redirect) {
                                setTimeout(() => {
                                    window.location.href = result.data.redirect;
                                }, 2000);
                            }
                        } else {
                            // Handle error responses
                            const data = result.data;

                            if (data.errors) {
                                // Field-specific errors
                                Object.keys(data.errors).forEach(field => {
                                    displayFieldError(field, data.errors[field]);
                                });
                            } else if (data.message) {
                                // General error message
                                displayGeneralError(data.message);
                            } else {
                                // Fallback error message
                                displayGeneralError(
                                    "An error occurred during registration. Please try again.");
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error details:', error);

                        // Reset button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;

                        // Show generic error
                        displayGeneralError(
                            "An unexpected error occurred. Please try again later.");
                    });
            });
        }

        // Function to display field-specific error
        function displayFieldError(field, errorMessage) {
            const input = donorForm.querySelector(`[name="${field}"]`);
            if (input) {
                const errorElement = document.createElement('p');
                errorElement.className = 'text-red-500 text-xs italic mt-1 error-message';
                errorElement.textContent = errorMessage;
                input.parentNode.appendChild(errorElement);
                input.classList.add('border-red-500');

                // Ensure the field is visible (switch to the correct step)
                const step = input.closest('.step');
                if (step && !step.classList.contains('active')) {
                    const stepId = step.id;
                    const stepNumber = stepId.split('-')[1];
                    showStep(parseInt(stepNumber));
                }
            } else {
                // If field not found, show as general error
                displayGeneralError(`${field}: ${errorMessage}`);
            }
        }

        // Function to display general error message
        function displayGeneralError(message) {
            const errorMessage = document.createElement('div');
            errorMessage.className =
                'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 error-message';
            errorMessage.innerHTML = `<p>${message}</p>`;
            donorForm.prepend(errorMessage);
        }

        // Function to clear all error messages and reset field borders
        function clearAllErrors() {
            // Remove all error message elements
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            // Reset all field borders
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.classList.remove('border-red-500');
            });

            // Remove any existing success messages
            document.querySelectorAll('.bg-green-100').forEach(el => {
                if (el.classList.contains('error-message') || el.querySelector('p')) {
                    el.remove();
                }
            });
        }

        // Function to show a specific step
        function showStep(stepNumber) {
            // Hide all steps
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active');
            });

            // Show the target step
            document.getElementById(`step-${stepNumber}`).classList.add('active');

            // Update current step
            currentStep = stepNumber;

            // Update step indicators
            updateStepIndicators();
        }
    });

    let currentStep = 1;

    function nextStep(step) {
        // Get all required fields in the current step
        const requiredFields = document.querySelectorAll(`#step-${step} [required]`);
        let isValid = true;

        // Clear previous error messages
        const errorMessages = document.querySelectorAll(`#step-${step} .error-message`);
        errorMessages.forEach(msg => msg.remove());

        // Reset field borders in current step
        const formFields = document.querySelectorAll(
            `#step-${step} input, #step-${step} select, #step-${step} textarea`);
        formFields.forEach(field => {
            field.classList.remove('border-red-500');
        });

        // Check each required field in current step only
        requiredFields.forEach(field => {
            if (field.type === 'radio') {
                // For radio buttons, check if any in the group is selected
                const radioGroup = document.querySelectorAll(
                    `#step-${step} input[name="${field.name}"]:checked`);
                if (radioGroup.length === 0) {
                    isValid = false;
                    showGroupError(field.closest('.mb-6'), 'Please select one option');
                }
            } else if (!field.value.trim()) {
                isValid = false;
                showFieldError(field, 'This field is required');
            }
        });

        // If validation fails, stop here
        if (!isValid) {
            return;
        }

        // Hide current step
        document.getElementById(`step-${step}`).classList.remove('active');

        // Show next step
        currentStep = step + 1;
        document.getElementById(`step-${currentStep}`).classList.add('active');

        // Update step indicators
        updateStepIndicators();
    }

    function prevStep(step) {
        // Hide current step
        document.getElementById(`step-${step}`).classList.remove('active');

        // Show previous step
        currentStep = step - 1;
        document.getElementById(`step-${currentStep}`).classList.add('active');

        // Update step indicators
        updateStepIndicators();
    }

    function showFieldError(field, message) {
        // Add red border to the field
        field.classList.add('border-red-500');

        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-sm mt-1';
        errorDiv.textContent = message;

        // Insert error message after the field
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }

    function showGroupError(container, message) {
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-sm mt-1';
        errorDiv.textContent = message;

        // Add error message to the container
        container.appendChild(errorDiv);
    }

    function updateStepIndicators() {
        // Reset all indicators
        for (let i = 1; i <= 3; i++) {
            const indicator = document.getElementById(`step-indicator-${i}`);
            const line = i < 3 ? document.getElementById(`step-line-${i}`) : null;

            if (i < currentStep) {
                // Completed steps
                indicator.classList.remove('bg-green-200', 'text-green-700');
                indicator.classList.add('bg-green-600', 'text-white');
                if (line) {
                    line.classList.remove('bg-green-200');
                    line.classList.add('bg-green-600');
                }
            } else if (i === currentStep) {
                // Current step
                indicator.classList.remove('bg-green-200', 'text-green-700');
                indicator.classList.add('bg-green-600', 'text-white');
                if (line) {
                    line.classList.remove('bg-green-600');
                    line.classList.add('bg-green-200');
                }
            } else {
                // Future steps
                indicator.classList.remove('bg-green-600', 'text-white');
                indicator.classList.add('bg-green-200', 'text-green-700');
                if (line) {
                    line.classList.remove('bg-green-600');
                    line.classList.add('bg-green-200');
                }
            }
        }
    }
    </script>
</body>

</html>