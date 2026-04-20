<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waza3ly - Food Distribution</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .carousel {
            position: relative;
            overflow: hidden;
            height: calc(100vh - 80px);
            min-height: 500px;
        }
        
        .carousel-inner {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .carousel-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-size: cover;
            background-position: center;
        }
        
        .carousel-item.active {
            opacity: 1;
        }
        
        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-align: center;
            width: 90%;
            max-width: 1000px;
            padding: 2rem;
            border-radius: 1rem;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        
        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 15;
        }
        
        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .carousel-indicator.active {
            background-color: #fff;
            transform: scale(1.2);
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
            color: #f59e0b;
        }
        
        #carouselInner .carousel-item:first-child {
            opacity: 1;
        }
        
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-20px) translateX(-50%);
            }
            60% {
                transform: translateY(-10px) translateX(-50%);
            }
        }
    </style>
</head>
<body class="bg-white">
    <?php include __DIR__ . '/../components/navbar.php'; ?>
    <div class="bg-white min-h-screen flex flex-col">
        <!-- Carousel Section -->
        <section class="relative">
            <div class="carousel">
                <div class="carousel-inner" id="carouselInner">
                    <div class="carousel-item active" style="background-image: url('/assets/images/CarouselImage0.jpg');"></div>
                </div>
                <div class="carousel-caption">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-white drop-shadow-lg">Connecting food donors with volunteers to help those in need.</h1>
                    <p class="text-lg md:text-xl mb-8 text-white font-semibold drop-shadow-lg">Helping reduce food waste by connecting food donors and volunteers for distribution to those who need it most.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/register" class="bg-yellow-500 hover:bg-yellow-400 text-green-800 px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Get Started</a>
                        <a href="/about" class="bg-green-700 hover:bg-green-600 text-white px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Learn More</a>
                    </div>
                </div>
                <div class="carousel-indicators" id="carouselIndicators"></div>
                <div class="scroll-down">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="mb-16">
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Our Promise</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-green-700 mb-6">Making food distribution simple and efficient</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">Connecting those with surplus food to volunteers who can deliver it to communities in need, creating a more equitable food system.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-md">
                        <div class="feature-icon text-green-600 text-4xl mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a4 4 0 112.76 3.77c.08-.65.14-1.3.14-1.77V6a4 4 0 00-8 0v7H4.5a2.5 2.5 0 100 5H8m9.5-5H14a2.5 2.5 0 100 5h3.5a2.5 2.5 0 002.5-2.5V14a2.5 2.5 0 00-2.5-2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-800 mb-4">Food Donation</h3>
                        <p class="text-gray-700">Easily donate surplus food from homes, restaurants, or events. Your contribution helps reduce waste and feed those in need.</p>
                    </div>
                    <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-md">
                        <div class="feature-icon text-green-600 text-4xl mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-800 mb-4">Volunteer Distribution</h3>
                        <p class="text-gray-700">Join as a volunteer to pick up and deliver food to communities in need, earning a small compensation for your valuable service.</p>
                    </div>
                    <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-md">
                        <div class="feature-icon text-green-600 text-4xl mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-800 mb-4">Community Impact</h3>
                        <p class="text-gray-700">Your participation helps create a more equitable food system, reducing waste while supporting vulnerable communities.</p>
                    </div>
                </div>
                
                <!-- Testimonials Section -->
                <div class="mb-16">
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full mb-4">Testimonials</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-green-700 mb-10">What Our Customers Say</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-xl shadow-md border border-green-100">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-green-200 flex items-center justify-center text-green-700 font-bold text-xl">
                                    AM
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Ahmed M.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"As a restaurant owner, Waza3ly helps me ensure our surplus food goes to those who need it instead of being wasted. The volunteers are amazing!"</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-md border border-green-100">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-green-200 flex items-center justify-center text-green-700 font-bold text-xl">
                                    SH
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Sara H.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"I volunteer with Waza3ly on weekends. It's rewarding to help distribute food to those in need, and the small compensation helps with my gas expenses."</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-md border border-green-100">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-green-200 flex items-center justify-center text-green-700 font-bold text-xl">
                                    MK
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Mohamed K.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"Our community center receives regular food deliveries through Waza3ly. It's made a huge difference for many families who struggle with food insecurity."</p>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-2xl p-8 md:p-12 shadow-xl">
                    <h2 class="text-3xl font-bold text-white mb-6">Ready to make a difference?</h2>
                    <p class="text-green-100 mb-8 max-w-2xl mx-auto">Join our community of food donors and volunteer distributors today. Help us create a more equitable food system.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/register" class="bg-yellow-500 hover:bg-yellow-400 text-green-800 px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Register Now</a>
                        <a href="/join" class="bg-white hover:bg-green-50 text-green-700 px-8 py-3 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Become a Volunteer</a>
                    </div>
                </div>
            </div>
        </section>

        <?php include __DIR__ . '/../components/footer.php'; ?>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalImages = 5; 
            const carouselInner = document.getElementById('carouselInner');
            const carouselIndicators = document.getElementById('carouselIndicators');
            
            // Create carousel items
            for (let i = 1; i < totalImages; i++) {
                const item = document.createElement('div');
                item.className = 'carousel-item';
                item.style.backgroundImage = `url('/assets/images/CarouselImage${i}.jpg')`;
                carouselInner.appendChild(item);
            }
            
            // Create carousel indicators
            for (let i = 0; i < totalImages; i++) {
                const indicator = document.createElement('div');
                indicator.className = i === 0 ? 'carousel-indicator active' : 'carousel-indicator';
                indicator.dataset.index = i;
                indicator.addEventListener('click', function() {
                    goToSlide(parseInt(this.dataset.index));
                });
                carouselIndicators.appendChild(indicator);
            }
            
            let currentIndex = 0;
            const items = document.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.carousel-indicator');
            
            function goToSlide(index) {
                items[currentIndex].classList.remove('active');
                indicators[currentIndex].classList.remove('active');
                currentIndex = index;
                items[currentIndex].classList.add('active');
                indicators[currentIndex].classList.add('active');
            }
            
            // Auto slide
            setInterval(() => {
                goToSlide((currentIndex + 1) % items.length);
            }, 5000);
            
            // Smooth scroll for the scroll-down button
            document.querySelector('.scroll-down').addEventListener('click', function() {
                window.scrollTo({
                    top: window.innerHeight,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
