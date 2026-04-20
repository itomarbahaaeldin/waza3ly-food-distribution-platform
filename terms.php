<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Waza3ly</title>
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
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Reset Your Password</h2>
                <p class="text-gray-600 text-center mb-6">Enter your email and new password below</p>

                <form id="reset-password-form" action="/api/profile/reset-password" method="POST">
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                            placeholder="Enter your email address" required>
                    </div>

                    <div class="mb-6">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New
                            Password</label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long and include a
                            mix of letters, numbers, and special characters.</p>
                    </div>

                    <div class="mb-6">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New
                            Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                            required>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg w-full">
                            Reset Password
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a href="/login" class="text-green-700 hover:text-green-600 text-sm font-medium">Back to Login</a>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
    // Function to handle form submission
    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent default form submission

        const form = event.target;

        // Clear previous messages
        clearMessages(form);

        // Disable submit button and show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = 'Processing...';

        // Get form data
        const formData = new FormData(form);

        // Send AJAX request
        fetch(form.action, {
                method: form.method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Parse JSON regardless of status code
                return response.json().then(data => {
                    // Add status to the data object for later use
                    return {
                        ...data,
                        status: response.status
                    };
                });
            })
            .then(data => {
                // Reset button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;

                if (data.success) {
                    // Show success message
                    showSuccessMessage(form, data.message);

                    // If there's a redirect, handle it
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        // Default redirect to login page after successful password reset
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                    }

                    // Reset form if it was successful
                    form.reset();
                } else {
                    // Show error messages
                    if (data.errors) {
                        // Field-specific errors
                        Object.keys(data.errors).forEach(field => {
                            // Find the input field
                            let input;
                            if (field === 'email') {
                                input = form.querySelector('[name="email"]');
                            } else if (field === 'new_password') {
                                input = form.querySelector('[name="new_password"]');
                            } else if (field === 'confirm_password') {
                                input = form.querySelector('[name="confirm_password"]');
                            } else {
                                input = form.querySelector(`[name="${field}"]`);
                            }

                            if (input) {
                                showFieldError(input, data.errors[field]);
                            } else {
                                // If field not found, show as general error
                                showErrorMessage(form, `${field}: ${data.errors[field]}`);
                            }
                        });
                    } else if (data.message) {
                        // General error message
                        showErrorMessage(form, data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Reset button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;

                // Show error message
                showErrorMessage(form, 'An unexpected error occurred. Please try again later.');
            });
    }

    // Function to clear all messages
    function clearMessages(form) {
        // Remove all error message elements
        form.querySelectorAll('.error-message').forEach(el => el.remove());

        // Reset all field borders
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
        });

        // Remove any existing success messages
        form.querySelectorAll('.success-message').forEach(el => el.remove());
    }

    // Function to show field error
    function showFieldError(field, message) {
        // Add red border to the field
        field.classList.add('border-red-500');

        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-xs italic mt-1';
        errorDiv.textContent = message;

        // Check if there's already an error message for this field
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Insert error message after the field
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }

    // Function to show success message
    function showSuccessMessage(form, message) {
        const successDiv = document.createElement('div');
        successDiv.className =
            'success-message bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
        successDiv.innerHTML = `<p>${message}</p>`;
        form.prepend(successDiv);

        // Scroll to the success message
        successDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Function to show error message
    function showErrorMessage(form, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className =
            'error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
        errorDiv.innerHTML = `<p>${message}</p>`;
        form.prepend(errorDiv);

        // Scroll to the error message
        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add form submission event listener
        const resetPasswordForm = document.getElementById('reset-password-form');
        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', handleFormSubmit);
        }
    });
    </script>
</body>

</html>