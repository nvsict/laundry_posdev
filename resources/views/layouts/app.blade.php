<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $storeName }} - POS</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .wrapper { display: flex; width: 100%; align-items: stretch; }
            #sidebar {
                min-width: 280px;
                max-width: 280px;
                background: #FFFFFF; /* Our original sidebar color */
                color: #000000ff;
                transition: all 0.3s;
            }
            #sidebar.active {
                margin-left: -280px; /* This makes it slide out */
            }
            #content {
                width: 100%;
                min-height: 100vh;
                transition: all 0.3s;
            }
            /* Make sure our main content fills the space */
            .main-content-area { width: 100%; }
            
            /* General sidebar link styling */
            .sidebar .nav-link { color: #4b5563; display: flex; align-items: center; }
            .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #dc2626; }
            .sidebar .nav-link i { width: 40px; text-align: center; font-size: 1.2rem; }
            .sidebar .nav-link.active {
                color: #ffffff;
                background-color: #dc2626; /* Bootstrap primary red */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="wrapper d-flex align-items-stretch">
            
            <nav class="sidebar" id="sidebar">
                <div class="p-4">
                    <a href="/dashboard" class="block text-center mb-4 text-2xl text-black   no-underline">
    Laundry POS
</a>
                    <ul class="nav nav-pills flex-column mb-auto px-2">
                        <li><a href="/dashboard" class="nav-link"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                        <hr class="text-secondary">
                        <li><a href="/pos" class="nav-link"><i class="fa-solid fa-cash-register"></i> <span>POS Screen</span></a></li>
                        <li><a href="/orders" class="nav-link"><i class="fa-solid fa-box-archive"></i> <span>Orders List</span></a></li>
                        <li><a href="/order-status" class="nav-link"><i class="fa-solid fa-truck-fast"></i> <span>Order Status</span></a></li>
                        <hr class="text-secondary">
                        <li><a href="/customers" class="nav-link"><i class="fa-solid fa-users"></i> <span>Customers</span></a></li>
                        <li><a href="/services" class="nav-link"><i class="fa-solid fa-shirt"></i> <span>Services</span></a></li>
                        <li><a href="/inventory" class="nav-link"><i class="fa-solid fa-boxes-stacked"></i> <span>Inventory</span></a></li>
                        <li><a href="/purchases" class="nav-link"><i class="fa-solid fa-dolly"></i> <span>Purchases</span></a></li>
                        <li><a href="/expenses" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Expenses</span></a></li>
                        @can('view reports')<li><a href="/reports" class="nav-link"><i class="fa-solid fa-chart-pie"></i> <span>Reports</span></a></li>@endcan
                        <hr class="text-secondary">
                        @can('manage staff')<li><a href="/staff" class="nav-link"><i class="fa-solid fa-user-shield"></i> <span>Staff</span></a></li>@endcan
                        @can('manage permissions')<li><a href="/permissions" class="nav-link"><i class="fa-solid fa-key"></i> <span>Permissions</span></a></li>@endcan
                        @can('access settings')<li><a href="/settings" class="nav-link"><i class="fa-solid fa-gear"></i> <span>Settings</span></a></li>@endcan
                    </ul>
                </div>
            </nav>

            <div id="content" class="main-content-area bg-gray-100 dark:bg-gray-900 p-4">
    @include('layouts.navigation')
    <main>@yield('content')</main>
</div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggler = document.getElementById('sidebarCollapse');
        
        // ## FIX: Define the missing variables here ##
        const currentPath = window.location.pathname;
        const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');

        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Active Link Logic
        sidebarLinks.forEach(link => {
            // Check if the link's href matches the current page's path
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
</script>
    </body>
</html>