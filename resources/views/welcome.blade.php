<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laundry POS</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb; /* Use a very light gray for the background */
        }
    </style>
</head>
<body class="antialiased">
    <div class="flex flex-col min-h-screen">
        <!-- Navigation Bar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo and Brand Name -->
                    <a href="/" class="flex items-center space-x-3">
                        <span class="text-2xl font-bold text-gray-800">Laundry POS</span>
                    </a>
                    
                    <!-- Login/Dashboard Links -->
                    <div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-300">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-300">Staff Login</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow">
            <div class="container mx-auto px-6 py-16 md:py-24">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Left Column: Text Content -->
                    <div class="text-center md:text-left">
                        <span class="text-lm font-bold uppercase text-red-600 tracking-wider">Effortless Laundry Management</span>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mt-3 mb-6 leading-tight">
                            Streamline Your Entire Laundry Business
                        </h1>
                        <p class="text-lg text-gray-600 mb-8">
                            Simplify and modernize your laundry operations. Manage orders, track customers, and generate reports with an intuitive and powerful point-of-sale system.
                        </p>
                        <a href="{{ route('login') }}" class="inline-block bg-red-600 text-white font-bold text-lg px-8 py-3 rounded-lg shadow-md hover:bg-red-700 transition-transform duration-300 hover:scale-105">
                            Access Login Portal &rarr;
                        </a>
                    </div>
                    
                    <!-- Right Column: Illustration -->
                    <div class="hidden md:block">
    <div class="bg-transparent p-8 -mt-14">
        <img src="img/pngwing.com.png" alt="Laundry POS System Illustration" class="rounded-xl -mt-14">
    </div>
</div>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-auto">
            <div class="container mx-auto px-6 py-4">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Laundry POS. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

