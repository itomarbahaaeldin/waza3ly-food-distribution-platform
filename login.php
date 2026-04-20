<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Waza3ly</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .contact-input {
            transition: all 0.3s ease;
        }
        
        .contact-input:focus {
            transform: translateY(-2px);
        }
        
        .contact-card {
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-green-700 to-green-600 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Get in Touch</h1>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">Have questions about Waza3ly? We're here to help. Reach out to our team using the contact information below.</p>
            </div>
        </section>

        <!-- Contact Information Cards -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 -mt-24">
                    <div class="bg-white rounded-xl shadow-lg p-6 contact-card">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-green-800 text-center mb-2">Phone</h3>
                        <p class="text-gray-600 text-center">+20 123 456 7890</p>
                        <p class="text-gray-600 text-center">Mon-Fri, 9am-6pm</p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 contact-card">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-green-800 text-center mb-2">Email</h3>
                        <p class="text-gray-600 text-center">info@waza3ly.com</p>
                        <p class="text-gray-600 text-center">support@waza3ly.com</p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 contact-card">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-green-800 text-center mb-2">Address</h3>
                        <p class="text-gray-600 text-center">123 Food Street, Kitchen District</p>
                        <p class="text-gray-600 text-center">Homemade City - 12345, Egypt</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form and Map -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Contact Us</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">Send Us a Message</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">Have a question, suggestion, or want to join our mission? Fill out the form below and we'll get back to you as soon as possible.</p>
                </div>
                
                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Contact Form -->
                    <div class="lg:w-1/2">
                        <form class="bg-white rounded-xl shadow-md p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent contact-input" placeholder="Your first name">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent contact-input" placeholder="Your last name">
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent contact-input" placeholder="Your email address">
                            </div>
                            
                            <div class="mb-6">
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent contact-input" placeholder="What is this regarding?">
                            </div>
                            
                            <div class="mb-6">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent contact-input" placeholder="Your message..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-700 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Send Message</button>
                        </form>
                    </div>
                    
                    <!-- Map -->
                    <div class="lg:w-1/2">
                        <div class="bg-white rounded-xl shadow-md p-4 h-full">
                            <!-- Google Maps Placeholder -->
                            <div class="bg-gray-200 rounded-lg h-full min-h-[400px] flex items-center justify-center">
                                <div class="text-center p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Google Maps Integration</h3>
                                    <p class="text-gray-500">This is a placeholder for the Google Maps integration. The actual map will be displayed here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">FAQ</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">Frequently Asked Questions</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">Find quick answers to common questions about Waza3ly.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-green-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-4">How can I become a volunteer?</h3>
                        <p class="text-gray-700">To become a volunteer, register on our platform, complete a brief orientation about food safety and distribution protocols, and specify your availability and service area.</p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-4">How do I donate food?</h3>
                        <p class="text-gray-700">To donate food, create an account, fill out the donation form with details about the food (type, quantity, pickup location, and time), and submit.</p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-4">Is there a cost to receive food?</h3>
                        <p class="text-gray-700">No, recipients never pay for the food they receive through Waza3ly. The food is donated, and the distribution cost is covered by the donor.</p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-4">How does the compensation system work?</h3>
                        <p class="text-gray-700">Donors provide a modest compensation to volunteers to cover transportation costs and time. This amount is suggested by our system based on distance, food volume, and effort required.</p>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <a href="/faq" class="inline-flex items-center text-green-700 hover:text-green-600 font-medium">
                        View all FAQs
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
