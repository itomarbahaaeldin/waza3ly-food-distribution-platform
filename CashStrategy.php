<nav class="bg-white shadow-md w-full sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
                    <img src="/assets/images/logo.png" alt="Waza3ly Logo"
                        class="h-16 w-auto transition-transform duration-300 hover:scale-105">
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button type="button" id="mobile-menu-button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-green-800 hover:text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg id="menu-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icon when menu is open -->
                    <svg id="close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="/"
                    class="text-lg text-green-800 font-semibold hover:text-yellow-500 transition duration-300 tracking-wide border-b-2 border-transparent hover:border-yellow-500 pb-1">Home</a>
                <a href="/about"
                    class="text-lg text-green-800 font-semibold hover:text-yellow-500 transition duration-300 tracking-wide border-b-2 border-transparent hover:border-yellow-500 pb-1">About
                    Waza3ly</a>
                <a href="/join"
                    class="text-lg text-green-800 font-semibold hover:text-yellow-500 transition duration-300 tracking-wide border-b-2 border-transparent hover:border-yellow-500 pb-1">Join
                    Us</a>
                <a href="/contact"
                    class="text-lg text-green-800 font-semibold hover:text-yellow-500 transition duration-300 tracking-wide border-b-2 border-transparent hover:border-yellow-500 pb-1">Contact
                    Us</a>
            </div>

            <!-- Auth buttons -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                <?php if (isset($_SESSION['user_id']) || isset($_SESSION['role'])): ?>
                <!-- User is logged in -->
                <div class="relative dropdown-container">
                    <button
                        class="flex items-center space-x-2 bg-yellow-500 hover:bg-yellow-400 text-green-800 px-6 py-2 rounded-full font-bold transition duration-300">
                        <span>
                            <?= ($_SESSION['role'] === 'admin') ? 'Admin' : htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div
                        class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                        <?php if ($_SESSION['role'] !== 'admin'): ?>
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">My
                            Profile</a>
                        <?php endif; ?>

                        <?php if ($_SESSION['role'] === 'volunteer'): ?>
                        <a href="/volunteer/dashboard"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Dashboard</a>
                        <?php elseif ($_SESSION['role'] === 'donor'): ?>
                        <a href="/donor/dashboard"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Dashboard</a>
                        <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <a href="/admin-dashboard"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Dashboard</a>
                        <?php endif; ?>

                        <a href="/api/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign Out</a>
                    </div>
                </div>
                <?php else: ?>
                <!-- User is not logged in -->
                <a href="/register"
                    class="bg-yellow-500 hover:bg-yellow-400 text-green-800 px-6 py-2 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Register</a>
                <a href="/login"
                    class="bg-green-700 hover:bg-green-600 text-white px-6 py-2 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">Sign
                    In</a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu, show/hide based on menu state -->
            <div id="mobile-menu"
                class="hidden md:hidden bg-white shadow-lg rounded-b-lg overflow-hidden transition-all duration-300 ease-in-out">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="/"
                        class="block px-3 py-2 rounded-md text-base font-medium text-green-800 hover:text-yellow-500 hover:bg-green-50">Home</a>
                    <a href="/about"
                        class="block px-3 py-2 rounded-md text-base font-medium text-green-800 hover:text-yellow-500 hover:bg-green-50">About
                        Waza3ly</a>
                    <a href="/join"
                        class="block px-3 py-2 rounded-md text-base font-medium text-green-800 hover:text-yellow-500 hover:bg-green-50">Join
                        Us</a>
                    <a href="/contact"
                        class="block px-3 py-2 rounded-md text-base font-medium text-green-800 hover:text-yellow-500 hover:bg-green-50">Contact
                        Us</a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center justify-center px-4">
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- User is logged in -->
                        <div class="w-full">
                            <button id="mobile-profile-button"
                                class="flex items-center justify-between w-full bg-green-100 hover:bg-green-200 text-green-800 px-6 py-2 rounded-full font-bold transition duration-300">
                                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="mobile-profile-dropdown"
                                class="hidden mt-2 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">My
                                    Profile</a>
                                <?php if($_SESSION['role'] === 'volunteer'): ?>
                                <a href="/volunteer/dashboard"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Dashboard</a>
                                <?php elseif($_SESSION['role'] === 'donor'): ?>
                                <a href="/donor/dashboard"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Dashboard</a>
                                <?php endif; ?>
                                <a href="/api/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign
                                    Out</a>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- User is not logged in -->
                        <div class="flex flex-col sm:flex-row w-full gap-4">
                            <a href="/register"
                                class="bg-yellow-500 hover:bg-yellow-400 text-green-800 px-6 py-2 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg w-full text-center">Register</a>
                            <a href="/login"
                                class="bg-green-700 hover:bg-green-600 text-white px-6 py-2 rounded-full font-bold transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg w-full text-center">Sign
                                In</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // Mobile profile dropdown toggle
    const mobileProfileButton = document.getElementById('mobile-profile-button');
    const mobileProfileDropdown = document.getElementById('mobile-profile-dropdown');

    if (mobileProfileButton && mobileProfileDropdown) {
        mobileProfileButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            mobileProfileDropdown.classList.toggle('hidden');
        });
    }

    // Desktop dropdown toggle
    const dropdownButtons = document.querySelectorAll('.dropdown-container button');

    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const menu = this.nextElementSibling;
            if (menu && menu.classList.contains('dropdown-menu')) {
                menu.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const dropdowns = document.querySelectorAll('.dropdown-menu, #mobile-profile-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    });

    // Hide mobile menu on window resize if screen becomes larger
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            if (mobileMenu) mobileMenu.classList.add('hidden');
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
        }
    });

    // Change navbar background on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('nav');
        if (navbar) {
            if (window.scrollY > 10) {
                navbar.classList.add('bg-white');
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        }
    });
});
</script>