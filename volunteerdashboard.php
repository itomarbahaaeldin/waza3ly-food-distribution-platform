<?php 
// Redirect to home page if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Waza3ly</title>
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

    .login-left-side {
        background-image: url('/assets/images/CarouselImage2.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .login-left-side::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%);
    }

    .login-content {
        position: relative;
        z-index: 10;
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-5xl rounded-2xl overflow-hidden shadow-2xl flex flex-col md:flex-row">
            <!-- Left side: Image and text -->
            <div class="login-left-side w-full md:w-1/2">
                <div class="login-content p-8 md:p-12 flex flex-col justify-center text-white h-full">
                    <h2 class="text-3xl font-bold mb-6">Welcome Back</h2>
                    <p class="text-lg mb-8">Sign in to continue your mission of reducing food waste and helping those in
                        need.</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p>Connect with volunteers and donors</p>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p>Manage your donations or distribution tasks</p>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p>Track your impact in the community</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side: Login form -->
            <div class="w-full md:w-1/2 bg-white p-8 md:p-12">
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-green-800 mb-2">Sign In</h2>
                        <p class="text-gray-600">Enter your credentials to access your account</p>
                    </div>

                    <form id="login-form" class="space-y-6" action="/api/login" method="POST">
                        <div>
                            <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">Email or
                                Username</label>
                            <input type="text" id="identifier" name="identifier"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Enter your email or username" required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent form-input"
                                placeholder="Enter your password" required>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <a href="/forgot_password"
                                    class="text-green-600 hover:text-green-500 font-medium">Forgot password?</a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Sign
                                In</button>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-gray-600">Don't have an account? <a href="/register"
                                class="text-green-600 hover:text-green-500 font-medium">Register now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get login form
    const loginForm = document.querySelector('#login-form');

    // Function to handle form submission
    function handleLoginSubmit(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous error messages
            clearErrors();

            // Get form data
            const formData = new FormData(form);

            // Show loading state on button
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Signing in...';

            // Send AJAX request
            fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;

                    if (data.success) {
                        // Show success message
                        showMessage(form, data.message, 'success');

                        // Redirect after a short delay
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        }
                    } else {
                        // Show error message
                        showMessage(form, data.message ||
                            'Login failed. Please check your credentials.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Reset button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;

                    // Show error message
                    showMessage(form, 'An unexpected error occurred. Please try again.', 'error');
                });
        });
    }

    // Apply event handler to the form
    if (loginForm) handleLoginSubmit(loginForm);

    // Function to clear error messages
    function clearErrors() {
        document.querySelectorAll('.alert-message').forEach(el => el.remove());
    }

    // Function to show message
    function showMessage(form, message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className =
            `alert-message ${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} px-4 py-3 rounded relative mb-4`;
        messageDiv.innerHTML = `<p>${message}</p>`;
        form.prepend(messageDiv);
    }
});
</script>

</html>