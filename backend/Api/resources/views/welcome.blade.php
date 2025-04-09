<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SKBT Motor Parts</title>

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

            /* Background styling */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(rgba(18, 18, 18, 0.9), rgba(18, 18, 18, 0.85)), url('/api/placeholder/1600/900') center/cover no-repeat;
                z-index: -2;
            }

            /* Main container */
            .motorcycle-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 2rem 1rem;
                position: relative;
            }

            /* Logo styling */
            .logo-wrapper {
                position: relative;
                margin-bottom: 1.5rem;
                transition: all 0.3s ease;
            }
            
            .logo-wrapper::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 110%;
                height: 110%;
                background: radial-gradient(circle, rgba(209,32,38,0.2) 0%, rgba(209,32,38,0) 70%);
                border-radius: 50%;
                z-index: -1;
            }
            
            .logo-wrapper:hover {
                transform: scale(1.02);
            }

            /* Dashboard card */
            .dashboard-panel {
                background-color: var(--deep-metal);
                border-radius: 6px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.4);
                overflow: hidden;
                position: relative;
                width: 100%;
                max-width: 450px;
                padding: 0;
            }
            
            /* Carbon fiber texture for panel header */
            .panel-header {
                background-color: var(--carbon-fiber);
                background-image: 
                    linear-gradient(45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
                    linear-gradient(-45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.03) 75%),
                    linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.03) 75%);
                background-size: 4px 4px;
                padding: 1.5rem 2rem;
                border-bottom: 3px solid var(--engine-red);
                position: relative;
            }
            
            /* Panel content area */
            .panel-content {
                padding: 1.5rem 2rem;
                position: relative;
            }
            
            /* Headings */
            .panel-heading {
                font-family: 'Bebas Neue', sans-serif;
                font-size: 2rem;
                letter-spacing: 1px;
                text-transform: uppercase;
                text-align: center;
                margin: 0;
                color: var(--polished-chrome);
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
                width: 100%;
            }
            
            .moto-button:hover {
                background: linear-gradient(to bottom, #E52D33 0%, var(--engine-red) 100%);
                box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            }
            
            .moto-button:active {
                transform: translateY(1px);
                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            }
            
            /* Footer area */
            .login-footer {
                margin-top: 1.5rem;
                text-align: center;
                color: rgba(232, 232, 232, 0.7);
                font-size: 0.9rem;
            }
            
            .login-footer a {
                color: var(--engine-red);
                text-decoration: none;
                transition: all 0.2s ease;
            }
            
            .login-footer a:hover {
                color: #E52D33;
                text-decoration: underline;
            }
            
            /* Separator */
            .separator {
                display: flex;
                align-items: center;
                text-align: center;
                margin: 1.5rem 0;
                color: rgba(232, 232, 232, 0.5);
            }
            
            .separator::before,
            .separator::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid rgba(232, 232, 232, 0.2);
            }
            
            .separator::before {
                margin-right: 0.5rem;
            }
            
            .separator::after {
                margin-left: 0.5rem;
            }

        </style>
    </head>
    <body>
        <div class="motorcycle-container">
            <!-- Logo -->
            <div class="logo-wrapper mb-6" style="width: 150px; height: 150px;">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="SKBT Motor Parts Logo" class="w-24 h-24">
                </a>
            </div>

            <!-- Dashboard panel -->
            <div class="dashboard-panel">
                <!-- Panel header -->
                <div class="panel-header">
                    <h1 class="panel-heading">SKBT Motor Parts</h1>
                </div>
                
                <!-- Panel content -->
                <div class="panel-content">
                    <!-- Login and Register Buttons -->
                    <div class="flex flex-col gap-4">
                        <a href="/login" class="moto-button">Log in</a>
                        <a href="/register" class="moto-button">Register</a>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2025 SKBT Motor Parts. All rights reserved.</p>
                <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Support</a></p>
            </div>
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
            });
        </script>
    </body>
</html>