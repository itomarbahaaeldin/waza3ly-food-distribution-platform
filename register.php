<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions - Waza3ly</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .faq-item {
            transition: all 0.3s ease;
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }
        
        .faq-item.active .faq-answer {
            max-height: 1000px;
        }
        
        .faq-toggle-icon {
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-toggle-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-green-800 mb-4">Frequently Asked Questions</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Find answers to the most common questions about Waza3ly's food donation and distribution service.</p>
        </div>

        <!-- FAQ Categories -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button class="faq-category-btn bg-green-700 text-white px-4 py-2 rounded-full font-medium hover:bg-green-600 transition-colors duration-300" data-category="all">All Questions</button>
            <button class="faq-category-btn bg-white text-green-700 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-300" data-category="general">General</button>
            <button class="faq-category-btn bg-white text-green-700 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-300" data-category="donors">Donors</button>
            <button class="faq-category-btn bg-white text-green-700 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-300" data-category="volunteers">Volunteers</button>
            <button class="faq-category-btn bg-white text-green-700 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-300" data-category="recipients">Recipients</button>
            <button class="faq-category-btn bg-white text-green-700 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-300" data-category="payment">Compensation</button>
        </div>

        <!-- FAQ Content -->
        <div class="bg-white shadow-md rounded-xl p-6 md:p-8 mb-8">
            <!-- General Questions -->
            <div class="mb-8 faq-category" data-category="general">
                <h2 class="text-2xl font-semibold text-green-700 mb-6">General Questions</h2>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">What is Waza3ly?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Waza3ly is a platform that connects food donors with volunteer distributors to help get surplus food to those in need. We aim to reduce food waste while addressing food insecurity in communities across Egypt.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">Where does Waza3ly operate?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Waza3ly currently operates in major cities across Egypt, including Cairo, Alexandria, Giza, Luxor, Aswan, and many others. We're continuously expanding to new locations. Check our footer section for a complete list of service areas.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How do I contact customer support?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>You can contact our customer support team via email at support@waza3ly.com, by phone at +20 123 456 7890, or through the contact form on our website. Our support team is available 7 days a week from 8 AM to 10 PM.</p>
                    </div>
                </div>

                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How does Waza3ly work?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Donors register and post available food for pickup. Volunteers in the area receive notifications and can choose to accept the distribution task. The volunteer picks up the food and delivers it to designated recipients or community centers. Donors provide a small compensation to volunteers for their time and transportation costs.</p>
                    </div>
                </div>
            </div>
            
            <!-- Ordering Questions -->
            <div class="mb-8 faq-category" data-category="donors">
                <h2 class="text-2xl font-semibold text-green-700 mb-6">Donors</h2>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How do I donate food?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>To donate food, create an account, fill out the donation form with details about the food (type, quantity, pickup location, and time), and submit. Nearby volunteers will be notified, and one will accept the task to pick up and distribute your donation.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">What types of food can I donate?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>You can donate any surplus food that is still safe to consume. This includes prepared meals, packaged foods, fresh produce, and non-perishable items. All food must be unexpired, properly stored, and in good condition. We have guidelines for food safety that all donors must follow.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How much should I compensate volunteers?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>We recommend a modest compensation based on distance, volume of food, and effort required. Our platform suggests an appropriate amount when you create your donation listing, typically ranging from 20-100 EGP depending on these factors. This compensation helps cover the volunteer's transportation costs and time.</p>
                    </div>
                </div>
            </div>
            
            <!-- Delivery Questions -->
            <div class="mb-8 faq-category" data-category="volunteers">
                <h2 class="text-2xl font-semibold text-green-700 mb-6">Volunteers</h2>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How do I become a volunteer?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>To become a volunteer, register on our platform, complete a brief orientation about food safety and distribution protocols, and specify your availability and service area. Once approved, you'll start receiving notifications about nearby food donation opportunities.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How much compensation will I receive?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Compensation varies based on the distance, volume of food, and effort required for each distribution task. Typically, you can expect to receive between 20-100 EGP per task. The exact amount is clearly displayed before you accept any distribution assignment.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">Where do I deliver the food?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Food is typically delivered to pre-designated locations such as community centers, shelters, or directly to individuals or families in need. The delivery location will be clearly specified in the distribution task details before you accept it.</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Questions -->
            <div class="mb-8 faq-category" data-category="payment">
                <h2 class="text-2xl font-semibold text-green-700 mb-6">Compensation</h2>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How does the compensation system work?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Donors provide a modest compensation to volunteers to cover transportation costs and time. This amount is suggested by our system based on distance, food volume, and effort required, but can be adjusted by the donor. Volunteers can see the compensation amount before accepting a task.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How do volunteers receive their compensation?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Compensation can be provided through our secure in-app payment system, mobile wallets, or cash at the time of pickup. The payment method is specified by the donor when creating the donation listing.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">Is there a minimum compensation amount?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>Yes, we have a minimum compensation guideline to ensure volunteers are fairly compensated for their time and transportation costs. The minimum amount varies based on location and distance but typically starts at 20 EGP for short-distance deliveries.</p>
                    </div>
                </div>
            </div>
            
            <!-- Home Chefs Questions -->
            <div class="mb-8 faq-category" data-category="recipients">
                <h2 class="text-2xl font-semibold text-green-700 mb-6">Recipients</h2>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">How can I receive food donations?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>If you're an individual or family in need, you can register as a recipient on our platform. Community centers, shelters, and other organizations serving those in need can also register as distribution points. Once registered, you'll be connected with food donations in your area.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">Is there a cost to receive food?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>No, recipients never pay for the food they receive through Waza3ly. The food is donated, and the distribution cost is covered by the donor. Our mission is to ensure that surplus food reaches those who need it most without any financial barriers.</p>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-center cursor-pointer">
                        <h3 class="text-lg font-medium text-gray-800">What types of food can I expect to receive?</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 faq-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="faq-answer mt-2 text-gray-600">
                        <p>The types of food vary depending on what's being donated. This can include prepared meals from restaurants or events, packaged foods, fresh produce, and non-perishable items. All food distributed through our platform meets safety standards and is suitable for consumption.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Still Have Questions -->
        <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-xl p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Still Have Questions?</h2>
            <p class="mb-6">Our customer support team is here to help you with any questions or concerns.</p>
            <a href="/contact" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-green-800 px-6 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Contact Us</a>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ accordion functionality
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.flex');
                
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                });
            });
            
            // FAQ category filtering
            const categoryButtons = document.querySelectorAll('.faq-category-btn');
            const faqCategories = document.querySelectorAll('.faq-category');
            
            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.getAttribute('data-category');
                    
                    // Update active button styling
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('bg-green-700', 'text-white');
                        btn.classList.add('bg-white', 'text-green-700');
                    });
                    
                    button.classList.remove('bg-white', 'text-green-700');
                    button.classList.add('bg-green-700', 'text-white');
                    
                    // Show/hide categories
                    if (category === 'all') {
                        faqCategories.forEach(cat => {
                            cat.style.display = 'block';
                        });
                    } else {
                        faqCategories.forEach(cat => {
                            if (cat.getAttribute('data-category') === category) {
                                cat.style.display = 'block';
                            } else {
                                cat.style.display = 'none';
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
