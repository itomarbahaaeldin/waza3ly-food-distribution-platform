<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Waza3ly - Homemade Food Distribution</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/images/CarouselImage1.jpg');
            background-size: cover;
            background-position: center;
            height: 500px;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
        }
        
        .team-member {
            transition: all 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }
        
        .team-member:hover .team-social {
            opacity: 1;
        }
        
        .team-social {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .timeline-item {
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #047857;
        }
        
        .timeline-dot {
            position: absolute;
            left: -9px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #047857;
            border: 4px solid white;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section flex items-center justify-center">
        <div class="text-center text-white px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">About Waza3ly</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto">Connecting food donors with volunteers to distribute food to those in need, reducing waste and fighting hunger.</p>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Our Mission</span>
                <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">Why We Started Waza3ly</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">We believe that food is a basic right, and no one should go hungry while perfectly good food goes to waste. Our mission is to bridge the gap between food surplus and food insecurity.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-green-50 p-6 rounded-xl shadow-md stat-card">
                    <div class="text-green-600 text-4xl mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2 text-center">Fighting Food Insecurity</h3>
                    <p class="text-gray-600 text-center">We help direct surplus food to communities and individuals experiencing food insecurity, ensuring resources reach those who need them most.</p>
                </div>
                
                <div class="bg-green-50 p-6 rounded-xl shadow-md stat-card">
                    <div class="text-green-600 text-4xl mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2 text-center">Economic Support</h3>
                    <p class="text-gray-600 text-center">We provide opportunities for volunteers to earn modest compensation while making a positive impact in their communities.</p>
                </div>
                
                <div class="bg-green-50 p-6 rounded-xl shadow-md stat-card">
                    <div class="text-green-600 text-4xl mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h.5A2.5 2.5 0 0020 5.5v-1.65M12 14.5V17m0 0v2.5M12 17h2.5M12 17h-2.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2 text-center">Reducing Food Waste</h3>
                    <p class="text-gray-600 text-center">We help reduce food waste by creating efficient distribution channels between food donors and recipients in need.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Our Story</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">How Waza3ly Began</h2>
                    <p class="text-gray-600 mb-4">
                        Waza3ly was born from a simple observation: while many restaurants, events, and households had surplus food, nearby communities were struggling with food insecurity.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Our founder, Ahmed, noticed that his local restaurant was throwing away perfectly good food at the end of each day, while just a few blocks away, families were struggling to put meals on the table.
                    </p>
                    <p class="text-gray-600 mb-4">
                        At the same time, many people in the community wanted to help but didn't know how to connect with those in need or lacked the resources to make a significant impact alone.
                    </p>
                    <p class="text-gray-600">
                        In 2020, Ahmed launched Waza3ly to bridge this gap, creating a platform that connects food donors with volunteer distributors who can deliver surplus food to those who need it most.
                    </p>
                </div>
                <div class="md:w-1/2">
                    <div class="relative pl-10">
                        <div class="timeline-item pl-8 pb-10">
                            <div class="timeline-dot"></div>
                            <h3 class="text-xl font-semibold text-green-700 mb-2">2020: The Beginning</h3>
                            <p class="text-gray-600">Waza3ly was founded in Cairo with just 5 home chefs and a simple website.</p>
                        </div>
                        <div class="timeline-item pl-8 pb-10">
                            <div class="timeline-dot"></div>
                            <h3 class="text-xl font-semibold text-green-700 mb-2">2021: Expansion</h3>
                            <p class="text-gray-600">Expanded to Alexandria and Giza, growing our network to over 100 home chefs.</p>
                        </div>
                        <div class="timeline-item pl-8 pb-10">
                            <div class="timeline-dot"></div>
                            <h3 class="text-xl font-semibold text-green-700 mb-2">2022: Technology Upgrade</h3>
                            <p class="text-gray-600">Launched our mobile app and implemented real-time delivery tracking.</p>
                        </div>
                        <div class="timeline-item pl-8">
                            <div class="timeline-dot"></div>
                            <h3 class="text-xl font-semibold text-green-700 mb-2">2023: Nationwide Reach</h3>
                            <p class="text-gray-600">Now operating in 15+ cities with over 500 home chefs and 50,000+ customers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Impact -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Our Impact</span>
                <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">Making a Difference</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Through our platform, we're creating positive change in communities across Egypt.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="bg-white p-6 rounded-xl shadow-md text-center stat-card">
                    <div class="text-green-600 text-4xl font-bold mb-2">500+</div>
                    <p class="text-gray-700 font-medium">Active Volunteers</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md text-center stat-card">
                    <div class="text-green-600 text-4xl font-bold mb-2">15+</div>
                    <p class="text-gray-700 font-medium">Cities Served</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md text-center stat-card">
                    <div class="text-green-600 text-4xl font-bold mb-2">50,000+</div>
                    <p class="text-gray-700 font-medium">Meals Distributed</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md text-center stat-card">
                    <div class="text-green-600 text-4xl font-bold mb-2">5 tons</div>
                    <p class="text-gray-700 font-medium">Food Waste Reduced Monthly</p>
                </div>
            </div>
            
            <div class="bg-green-50 p-8 rounded-xl shadow-md">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="md:w-1/3">
                        <img src="assets/images/communityimpact.png" alt="Community Impact" class="rounded-xl shadow-md">
                    </div>
                    <div class="md:w-2/3">
                        <h3 class="text-2xl font-semibold text-green-800 mb-4">Community Impact</h3>
                        <p class="text-gray-600 mb-4">
                            Beyond connecting chefs and customers, Waza3ly is committed to giving back to the communities we serve. Through our "Meals with Meaning" program, we donate a portion of our proceeds to local food banks and community kitchens.
                        </p>
                        <p class="text-gray-600 mb-4">
                            We also organize regular cooking workshops where our home chefs teach culinary skills to underprivileged youth, helping them develop valuable skills for future employment.
                        </p>
                        <p class="text-gray-600">
                            Our environmental initiatives include using eco-friendly packaging and implementing efficient delivery routes to reduce our carbon footprint.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">How It Works</span>
                <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">Our Process</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Connecting home chefs with hungry customers is simple and efficient.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center text-green-700 font-bold text-xl mb-4">1</div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2">Donor Registration</h3>
                    <p class="text-gray-600">Restaurants, event organizers, and individuals register to donate surplus food, specifying quantity, type, and pickup times.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center text-green-700 font-bold text-xl mb-4">2</div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2">Volunteer Assignment</h3>
                    <p class="text-gray-600">Nearby volunteers receive notifications about available food donations and can choose to accept distribution tasks.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center text-green-700 font-bold text-xl mb-4">3</div>
                    <h3 class="text-xl font-semibold text-green-800 mb-2">Distribution & Delivery</h3>
                    <p class="text-gray-600">Volunteers pick up the food and deliver it to designated community centers, shelters, or individuals in need.</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-xl p-8 text-white">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="md:w-2/3">
                        <h3 class="text-2xl font-semibold mb-4">Quality Assurance</h3>
                        <p class="mb-4">
                            At Waza3ly, quality is our top priority. We implement a rigorous quality control process:
                        </p>
                        <ul class="list-disc pl-6 mb-6">
                            <li class="mb-2">Regular kitchen inspections to ensure cleanliness and safety</li>
                            <li class="mb-2">Food quality checks and taste testing</li>
                            <li class="mb-2">Customer feedback monitoring and chef ratings</li>
                            <li class="mb-2">Continuous training for home chefs on food safety and presentation</li>
                        </ul>
                        <p>
                            This ensures that every meal delivered through our platform meets our high standards for taste, quality, and safety.
                        </p>
                    </div>
                    <div class="md:w-1/3">
                        <img src="assets/images/qualityassurance.jpg" alt="Quality Assurance" class="rounded-xl shadow-md">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Our Team</span>
                <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">The People Behind Waza3ly</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Meet the dedicated team working to connect communities through food.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="bg-white rounded-xl shadow-md overflow-hidden team-member">
                    <img src="assets/images/person.png" alt="Ahmed Hassan" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-1">Ahmed Hassan</h3>
                        <p class="text-green-600 mb-4">Founder & CEO</p>
                        <p class="text-gray-600 text-sm">Former chef with a passion for connecting people through food and supporting local communities.</p>
                        <div class="mt-4 flex space-x-3 team-social">
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden team-member">
                    <img src="assets/images/person.png" alt="Nour Ibrahim" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-1">Nour Ibrahim</h3>
                        <p class="text-green-600 mb-4">Chief Operations Officer</p>
                        <p class="text-gray-600 text-sm">Logistics expert with experience in food delivery and supply chain management.</p>
                        <div class="mt-4 flex space-x-3 team-social">
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden team-member">
                    <img src="assets/images/person.png" alt="Laila Mahmoud" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-1">Laila Mahmoud</h3>
                        <p class="text-green-600 mb-4">Head of Chef Relations</p>
                        <p class="text-gray-600 text-sm">Culinary expert who works directly with our home chefs to ensure quality and authenticity.</p>
                        <div class="mt-4 flex space-x-3 team-social">
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden team-member">
                    <img src="assets/images/person.png" alt="Omar Farid" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-1">Omar Farid</h3>
                        <p class="text-green-600 mb-4">Chief Technology Officer</p>
                        <p class="text-gray-600 text-sm">Tech innovator focused on creating seamless digital experiences for chefs and customers alike.</p>
                        <div class="mt-4 flex space-x-3 team-social">
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </p>
                            <p class="text-green-600 hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Us CTA -->
    <section class="py-16 bg-gradient-to-r from-green-700 to-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Join the Waza3ly Movement</h2>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">Whether you have food to donate or time to volunteer as a distributor, your contribution can make a real difference in fighting hunger and reducing waste.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/volunteer" class="bg-yellow-500 hover:bg-yellow-400 text-green-800 px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Become a Volunteer</a>
                <a href="/customer" class="bg-white hover:bg-green-50 text-green-700 px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Register as Donor</a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add scroll reveal animations
            const revealElements = document.querySelectorAll('.stat-card, .team-member');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });
            
            revealElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
