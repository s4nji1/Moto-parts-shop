<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SKBT Motorcycle Parts') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=barlow:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=bebas+neue:400&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --primary-black: #121212;
                --deep-metal: #1E1E1E;
                --polished-chrome: #E8E8E8;
                --matte-gray: #2F2F2F;
                --engine-red: #D12026;
                --fuel-yellow: #F9A602;
                --carbon-fiber: #333333;
                --exhaust-blue: #2B6CC4;
                --leather-brown: #844C2C;
            }
            
            body {
                font-family: 'Barlow', sans-serif;
                background-color: var(--primary-black);
                color: var(--polished-chrome);
                position: relative;
                overflow-x: hidden;
            }
            
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(rgba(18, 18, 18, 0.95), rgba(18, 18, 18, 0.9)), url('/api/placeholder/1600/900') center/cover no-repeat;
                z-index: -2;
            }
            
            /* Navigation styling */
            .nav-container {
                background-color: var(--deep-metal);
                border-bottom: 3px solid var(--engine-red);
                box-shadow: 0 4px 12px rgba(0,0,0,0.5);
                position: relative;
                z-index: 10;
            }
            
            .nav-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem 1.5rem;
            }
            
            .nav-logo {
                display: flex;
                align-items: center;
            }
            
            .nav-logo img, .nav-logo svg {
                height: 2.5rem;
                width: auto;
                transition: transform 0.3s ease;
            }
            
            .nav-links {
                display: flex;
                gap: 1.5rem;
            }
            
            .nav-link {
                font-family: 'Barlow', sans-serif;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--polished-chrome);
                padding: 0.5rem 0.75rem;
                position: relative;
                transition: all 0.3s ease;
            }
            
            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -3px;
                left: 0;
                width: 0;
                height: 3px;
                background-color: var(--engine-red);
                transition: all 0.3s ease;
            }
            
            .nav-link:hover {
                color: white;
            }
            
            .nav-link:hover::after, 
            .nav-link.active::after {
                width: 100%;
            }
            
            .nav-link.active {
                color: white;
                font-weight: 700;
            }
            
            /* Page header styling */
            .page-header {
                background-color: var(--carbon-fiber);
                background-image: 
                    linear-gradient(45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
                    linear-gradient(-45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.03) 75%),
                    linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.03) 75%);
                background-size: 6px 6px;
                border-bottom: 2px solid rgba(255,255,255,0.1);
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                position: relative;
            }
            
            .page-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 2px;
                background: linear-gradient(90deg, 
                    var(--engine-red) 0%, 
                    var(--engine-red) 100%);
                z-index: 2;
            }
            
            .header-content {
                padding: 1.5rem;
            }
            
            .header-title {
                font-family: 'Bebas Neue', sans-serif;
                font-size: 1.75rem;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin: 0;
                color: white;
            }
            
            /* Main content area */
            .main-content {
                padding: 2rem 1.5rem;
                min-height: calc(100vh - 64px - 68px);
            }
            
            /* Content card styling */
            .content-panel {
                background-color: var(--deep-metal);
                border-radius: 4px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                overflow: hidden;
                margin-bottom: 1.5rem;
            }
            
            .panel-header {
                padding: 1.25rem;
                border-bottom: 2px solid var(--engine-red);
                position: relative;
            }
            
            .panel-title {
                font-family: 'Barlow', sans-serif;
                font-weight: 700;
                font-size: 1.25rem;
                text-transform: uppercase;
                margin: 0;
                color: white;
            }
            
            .panel-body {
                padding: 1.25rem;
            }
            
            /* Buttons */
            .moto-button {
                background: linear-gradient(to bottom, var(--engine-red) 0%, #9A1A1E 100%);
                color: white;
                border: none;
                border-radius: 4px;
                padding: 0.75rem 1.5rem;
                font-family: 'Barlow', sans-serif;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                transition: all 0.2s ease;
            }
            
            .moto-button:hover {
                background: linear-gradient(to bottom, #E52D33 0%, var(--engine-red) 100%);
                box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            }
            
            .moto-button:active {
                transform: translateY(1px);
                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            }
            
            /* Dropdown styling */
            .dropdown-menu {
                background-color: var(--carbon-fiber);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 4px;
                box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            }
            
            .dropdown-item {
                padding: 0.75rem 1rem;
                color: var(--polished-chrome);
                transition: all 0.2s ease;
            }
            
            .dropdown-item:hover {
                background-color: rgba(209,32,38,0.15);
                color: white;
            }
            
            /* Form elements */
            .form-input {
                background-color: var(--matte-gray);
                border: 1px solid rgba(255,255,255,0.1);
                color: var(--polished-chrome);
                border-radius: 4px;
                padding: 0.75rem 1rem;
                transition: all 0.3s ease;
                width: 100%;
                margin-bottom: 1rem;
                box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
            }
            
            .form-input:focus {
                border-color: var(--engine-red);
                box-shadow: 0 0 0 2px rgba(209,32,38,0.2), inset 0 1px 3px rgba(0,0,0,0.3);
                outline: none;
            }
            
            /* Table styling */
            .moto-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }
            
            .moto-table th {
                background-color: var(--carbon-fiber);
                color: white;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 0.75rem 1rem;
                border-bottom: 2px solid var(--engine-red);
            }
            
            .moto-table td {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            
            .moto-table tr:hover td {
                background-color: rgba(255,255,255,0.02);
            }
            
            /* Product grid */
            .product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 1.5rem;
            }
            
            .product-card {
                background-color: var(--deep-metal);
                border-radius: 4px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
            }
            
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            }
            
            .product-image {
                height: 180px;
                background-color: var(--carbon-fiber);
                overflow: hidden;
                position: relative;
            }
            
            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                transition: all 0.5s ease;
            }
            
            .product-card:hover .product-image img {
                transform: scale(1.05);
            }
            
            .product-details {
                padding: 1rem;
            }
            
            .product-title {
                font-family: 'Barlow', sans-serif;
                font-weight: 700;
                font-size: 1rem;
                margin: 0 0 0.5rem 0;
                color: white;
            }
            
            .product-price {
                color: var(--engine-red);
                font-weight: 700;
                font-size: 1.15rem;
                margin: 0 0 0.5rem 0;
            }
            
            .product-category {
                font-size: 0.8rem;
                color: #999;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            /* Footer */
            .footer {
                background-color: var(--deep-metal);
                border-top: 3px solid var(--engine-red);
                padding: 2rem 1.5rem;
                color: var(--polished-chrome);
            }
            
            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2rem;
            }
            
            .footer-logo img {
                height: 2.5rem;
                margin-bottom: 1rem;
            }
            
            .footer-title {
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 1rem;
                color: white;
            }
            
            .footer-links a {
                display: block;
                color: var(--polished-chrome);
                margin-bottom: 0.5rem;
                transition: all 0.2s ease;
            }
            
            .footer-links a:hover {
                color: white;
                transform: translateX(3px);
            }
            
            .footer-contact {
                margin-bottom: 0.5rem;
            }
            
            .copyright {
                text-align: center;
                padding-top: 2rem;
                font-size: 0.9rem;
                color: #999;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <!-- Navigation -->
            <div class="nav-container">
                @include('layouts.navigation')
            </div>
            <!-- Page Heading -->
            @isset($header)
                <header class="page-header">
                    <div class="max-w-7xl mx-auto header-content">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <!-- Page Content -->
            <main class="main-content max-w-7xl mx-auto">
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="max-w-7xl mx-auto footer-content">
                    <div>
                        <div class="footer-logo">
                            <img src="{{ asset('images/logo.png') }}" alt="SKBT Motorcycle Parts"/>
                        </div>
                        <p>Premium motorcycle parts and accessories for enthusiasts and professionals alike.</p>
                    </div>
                    <div>
                        <h3 class="footer-title">Quick Links</h3>
                        <div class="footer-links">
                            <a href="#">Products</a>
                            <a href="#">Brands</a>
                            <a href="#">Sale Items</a>
                            <a href="#">Compatibility Checker</a>
                            <a href="#">Blog</a>
                        </div>
                    </div>
                    <div>
                        <h3 class="footer-title">Customer Service</h3>
                        <div class="footer-links">
                            <a href="#">My Account</a>
                            <a href="#">Order Status</a>
                            <a href="#">Returns & Exchanges</a>
                            <a href="#">Shipping Policy</a>
                            <a href="#">Contact Us</a>
                        </div>
                    </div>
                    <div>
                        <h3 class="footer-title">Contact Us</h3>
                        <div class="footer-contact">
                            <p>1234 Motorcycle Ave.</p>
                            <p>Speed City, SC 12345</p>
                            <p>Phone: (555) 123-4567</p>
                            <p>Email: support@skbt.com</p>
                        </div>
                    </div>
                </div>
                <div class="copyright">
                    &copy; 2025 SKBT Motorcycle Parts. All rights reserved.
                </div>
            </footer>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Apply styles to form elements
                const inputs = document.querySelectorAll('input:not([type="checkbox"]):not([type="radio"]), textarea, select');
                inputs.forEach(input => {
                    input.classList.add('form-input');
                });
                
                // Apply styles to buttons
                const buttons = document.querySelectorAll('button, [type="submit"], [type="button"], .btn');
                buttons.forEach(button => {
                    button.classList.add('moto-button');
                });
                
                // Apply styles to tables
                const tables = document.querySelectorAll('table:not(.moto-table)');
                tables.forEach(table => {
                    table.classList.add('moto-table');
                });
                
                // Apply styles to card-like elements
                const cards = document.querySelectorAll('.card, .bg-white, .dark\\:bg-gray-800');
                cards.forEach(card => {
                    if (!card.classList.contains('nav-container') && !card.classList.contains('page-header')) {
                        card.classList.add('content-panel');
                    }
                });
                
                // Add active class to current navigation link
                const currentPath = window.location.pathname;
                const navLinks = document.querySelectorAll('.nav-link');
                
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === currentPath) {
                        link.classList.add('active');
                    }
                });
            });
        </script>
    </body>
</html>