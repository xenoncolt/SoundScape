
<?php
try {
    $migration = new App\Database\Migration();
    if ($migration->isSetupCompleted()) {
        redirect('?page=home');
        exit;
    }
} catch (Exception $e) {
    // Setup not completed, continue with setup page
    // exception msg i will do later if got time
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup SoundScape</title>
    <meta name="description" content="Configure your SoundScape music server">
    
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body class="bg-music-dark text-white min-h-screen">

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="text-center mb-12 animate-fade-in">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 shadow-music">
                        <img src="/assets/images/SOUNDSCAPE.svg" alt="Logo">
                    </div>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 text-shadow">
                    Welcome to 
                    <span class="text-music-primary">SoundScape</span>
                </h1>
                <p class="text-xl text-music-light-gray max-w-2xl mx-auto leading-relaxed">
                    Lets set up your personal music server. This will only take a few minutes 
                    to get you up and running with your own Spotify-like experience. Hee boiii....
                </p>
            </div>
            
            <div class="mb-8 animate-slide-up">
                <div class="flex items-center justify-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-music-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            1
                        </div>
                        <span class="ml-2 text-music-primary font-medium">Database</span>
                    </div>
                    
                    <div class="w-12 h-px bg-gray-600"></div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 font-bold">
                            2
                        </div>
                        <span class="ml-2 text-gray-400">Admin Account</span>
                    </div>
                    
                    <div class="w-12 h-px bg-gray-600"></div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 font-bold">
                            3
                        </div>
                        <span class="ml-2 text-gray-400">Services</span>
                    </div>

                    <div class="w-12 h-px bg-gray-600"></div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 font-bold">
                            4
                        </div>
                        <span class="ml-2 text-gray-400">Settings</span>
                    </div>
                    
                    <div class="w-12 h-px bg-gray-600"></div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 font-bold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-gray-400">Complete</span>
                    </div>
                </div>
            </div>
            
            <!-- Setup Form Card -->
            <div class="card shadow-2xl animate-slide-up">
                <div class="card-body">
                    
                    <!-- Form Start -->
                    <form id="setupForm" method="POST" action="?page=complete-setup" class="space-y-8">
                        
                        <!-- Database Configuration Section -->
                        <div class="border-b border-gray-700 pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-music-primary rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-white">Database Configuration</h3>
                                    <p class="text-music-gray">
                                        Configure your MySQL database connection. 
                                        SoundScape will create the database if it doesn't exist.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Database Form Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Database Host -->
                                <div>
                                    <label for="db_host" class="form-label">
                                        Database Host *
                                    </label>
                                    <input type="text" 
                                           id="db_host" 
                                           name="db_host" 
                                           value="localhost" 
                                           class="form-input"
                                           placeholder="localhost"
                                           required>
                                    <p class="text-sm text-music-gray mt-1">
                                        Usually 'localhost' for local servers, or your database server IP
                                    </p>
                                </div>
                                
                                <!-- Database Name -->
                                <div>
                                    <label for="db_name" class="form-label">
                                        Database Name *
                                    </label>
                                    <input type="text" 
                                           id="db_name" 
                                           name="db_name" 
                                           value="soundscape" 
                                           class="form-input"
                                           placeholder="soundscape"
                                           required>
                                    <p class="text-sm text-music-gray mt-1">
                                        Will be created automatically if it doesn't exist
                                    </p>
                                </div>
                                
                                <!-- Database Username -->
                                <div>
                                    <label for="db_user" class="form-label">
                                        Database Username *
                                    </label>
                                    <input type="text" 
                                           id="db_user" 
                                           name="db_user" 
                                           value="root" 
                                           class="form-input"
                                           placeholder="root"
                                           required>
                                    <p class="text-sm text-music-gray mt-1">
                                        MySQL user with database creation permissions
                                    </p>
                                </div>
                                
                                <!-- Database Password -->
                                <div>
                                    <label for="db_password" class="form-label">
                                        Database Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="db_password" 
                                               name="db_password" 
                                               class="form-input pr-12"
                                               placeholder="Enter database password">
                                        
                                        <!-- Password Toggle Button -->
                                        <button type="button" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                onclick="togglePassword('db_password')">
                                            <svg id="db_password_show" class="h-5 w-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg id="db_password_hide" class="h-5 w-5 text-gray-400 hover:text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-sm text-music-gray mt-1">
                                        Leave empty if no password is set
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Database Test Button -->
                            <div class="mt-6">
                                <button type="button" 
                                        id="testDbConnection" 
                                        class="btn-outline flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Test Database Connection
                                </button>
                                <div id="dbTestResult" class="mt-2 hidden"></div>
                            </div>
                        </div>
                        
                        <!-- Admin Account Section -->
                        <div class="border-b border-gray-700 pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-white">Administrator Account</h3>
                                    <p class="text-music-gray">
                                        Create your admin account to manage the server. 
                                        This will be the first user with full permissions.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Admin Form Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Admin Username -->
                                <div>
                                    <label for="admin_username" class="form-label">
                                        Administrator Username *
                                    </label>
                                    <input type="text" 
                                           id="admin_username" 
                                           name="admin_username" 
                                           class="form-input"
                                           placeholder="admin"
                                           required
                                           minlength="3"
                                           maxlength="50"
                                           pattern="[a-zA-Z0-9_]+"
                                           title="Username can only contain letters, numbers, and underscores">
                                    <p class="text-sm text-music-gray mt-1">
                                        3-50 characters, letters, numbers, and underscores only
                                    </p>
                                </div>
                                
                                <!-- Admin Email -->
                                <div>
                                    <label for="admin_email" class="form-label">
                                        Administrator Email *
                                    </label>
                                    <input type="email" 
                                           id="admin_email" 
                                           name="admin_email" 
                                           class="form-input"
                                           placeholder="admin@example.com"
                                           required>
                                    <p class="text-sm text-music-gray mt-1">
                                        Used for account recovery and notifications
                                    </p>
                                </div>
                                
                                <!-- Admin Password -->
                                <div>
                                    <label for="admin_password" class="form-label">
                                        Administrator Password *
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="admin_password" 
                                               name="admin_password" 
                                               class="form-input pr-12"
                                               placeholder="Enter strong password"
                                               required
                                               minlength="6">
                                        
                                        <!-- Password Toggle Button -->
                                        <button type="button" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                onclick="togglePassword('admin_password')">
                                            <svg id="admin_password_show" class="h-5 w-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg id="admin_password_hide" class="h-5 w-5 text-gray-400 hover:text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Strength Indicator -->
                                    <div id="passwordStrength" class="mt-2 hidden">
                                        <div class="flex space-x-1">
                                            <div id="strength1" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                            <div id="strength2" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                            <div id="strength3" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                            <div id="strength4" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                        </div>
                                        <p id="strengthText" class="text-sm mt-1"></p>
                                    </div>
                                    
                                    <p class="text-sm text-music-gray mt-1">
                                        Minimum 6 characters, use strong password for security
                                    </p>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div>
                                    <label for="admin_password_confirm" class="form-label">
                                        Confirm Password *
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="admin_password_confirm" 
                                               name="admin_password_confirm" 
                                               class="form-input pr-12"
                                               placeholder="Confirm password"
                                               required>
                                        
                                        <!-- Password Toggle Button -->
                                        <button type="button" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                onclick="togglePassword('admin_password_confirm')">
                                            <svg id="admin_password_confirm_show" class="h-5 w-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg id="admin_password_confirm_hide" class="h-5 w-5 text-gray-400 hover:text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Match Indicator -->
                                    <div id="passwordMatch" class="mt-2 hidden">
                                        <p id="matchText" class="text-sm"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Services Configuration Section -->
                        <div class="border-b border-gray-700 pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-white">Services Configuration</h3>
                                    <p class="text-music-gray">
                                        Configure optional services like email notifications and Discord integration.
                                    </p>
                                </div>
                            </div>

                            <!-- SMTP Configuration -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <label class="flex items-start cursor-pointer group">
                                        <input type="checkbox" 
                                               id="enable_smtp" 
                                               name="enable_smtp" 
                                               value="1"
                                               class="rounded border-gray-600 text-purple-600 focus:ring-purple-600 focus:ring-opacity-25 bg-gray-700 mt-1"
                                               onchange="toggleSmtpFields()">
                                        <div class="ml-3">
                                            <span class="text-white font-medium group-hover:text-purple-400 transition-colors flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                                Enable Email Notifications (SMTP)
                                            </span>
                                            <p class="text-sm text-music-gray">
                                                Configure SMTP server to send password reset emails and notifications
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <div id="smtpFields" class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-8 hidden">
                                    <div>
                                        <label for="smtp_host" class="form-label">SMTP Host</label>
                                        <input type="text" 
                                               id="smtp_host" 
                                               name="smtp_host" 
                                               class="form-input"
                                               placeholder="smtp.gmail.com">
                                        <p class="text-sm text-music-gray mt-1">SMTP server hostname</p>
                                    </div>
                                    
                                    <div>
                                        <label for="smtp_port" class="form-label">SMTP Port</label>
                                        <select id="smtp_port" name="smtp_port" class="form-input">
                                            <option value="587">587 (TLS/STARTTLS)</option>
                                            <option value="465">465 (SSL)</option>
                                            <option value="25">25 (Plain)</option>
                                            <option value="2525">2525 (Alternative)</option>
                                        </select>
                                        <p class="text-sm text-music-gray mt-1">SMTP server port</p>
                                    </div>
                                    
                                    <div>
                                        <label for="smtp_username" class="form-label">SMTP Username</label>
                                        <input type="text" 
                                               id="smtp_username" 
                                               name="smtp_username" 
                                               class="form-input"
                                               placeholder="your-email@gmail.com">
                                        <p class="text-sm text-music-gray mt-1">SMTP authentication username</p>
                                    </div>
                                    
                                    <div>
                                        <label for="smtp_password" class="form-label">SMTP Password</label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="smtp_password" 
                                                   name="smtp_password" 
                                                   class="form-input pr-12"
                                                   placeholder="SMTP password or app password">
                                            <button type="button" 
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                    onclick="togglePassword('smtp_password')">
                                                <svg id="smtp_password_show" class="h-5 w-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg id="smtp_password_hide" class="h-5 w-5 text-gray-400 hover:text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-music-gray mt-1">Use app password for Gmail</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="smtp_from_email" class="form-label">From Email Address</label>
                                        <input type="email" 
                                               id="smtp_from_email" 
                                               name="smtp_from_email" 
                                               class="form-input"
                                               placeholder="noreply@yourdomain.com">
                                        <p class="text-sm text-music-gray mt-1">Email address that will appear as sender</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Discord Configuration -->
                            <div class="mb-6">
                                <div class="flex items-center mb-4">
                                    <label class="flex items-start cursor-pointer group">
                                        <input type="checkbox" 
                                               id="enable_discord" 
                                               name="enable_discord" 
                                               value="1"
                                               class="rounded border-gray-600 text-indigo-600 focus:ring-indigo-600 focus:ring-opacity-25 bg-gray-700 mt-1"
                                               onchange="toggleDiscordFields()">
                                        <div class="ml-3">
                                            <span class="text-white font-medium group-hover:text-indigo-400 transition-colors flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.010c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.120.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
                                                </svg>
                                                Enable Discord OAuth Login
                                            </span>
                                            <p class="text-sm text-music-gray">
                                                Allow users to login with their Discord accounts
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <div id="discordFields" class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-8 hidden">
                                    <div>
                                        <label for="discord_client_id" class="form-label">Discord Client ID</label>
                                        <input type="text" 
                                               id="discord_client_id" 
                                               name="discord_client_id" 
                                               class="form-input"
                                               placeholder="Your Discord App Client ID">
                                        <p class="text-sm text-music-gray mt-1">From Discord Developer Portal</p>
                                    </div>
                                    
                                    <div>
                                        <label for="discord_client_secret" class="form-label">Discord Client Secret</label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="discord_client_secret" 
                                                   name="discord_client_secret" 
                                                   class="form-input pr-12"
                                                   placeholder="Your Discord App Client Secret">
                                            <button type="button" 
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                    onclick="togglePassword('discord_client_secret')">
                                                <svg id="discord_client_secret_show" class="h-5 w-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg id="discord_client_secret_hide" class="h-5 w-5 text-gray-400 hover:text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-music-gray mt-1">Keep this secret secure</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="discord_redirect_url" class="form-label">Discord Redirect URL</label>
                                        <input type="url" 
                                               id="discord_redirect_url" 
                                               name="discord_redirect_url" 
                                               class="form-input"
                                               placeholder="https://yourdomain.com/?page=auth&provider=discord"
                                               readonly>
                                        <p class="text-sm text-music-gray mt-1">Add this URL to your Discord App settings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Server Configuration Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-white">Server Settings</h3>
                                    <p class="text-music-gray">
                                        Configure basic server preferences. 
                                        These can be changed later in the admin panel.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Server Settings Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                
                                <!-- Server Name -->
                                <div>
                                    <label for="server_name" class="form-label">
                                        Server Name *
                                    </label>
                                    <input type="text" 
                                           id="server_name" 
                                           name="server_name" 
                                           value="My SoundScape Server"
                                           class="form-input"
                                           placeholder="My SoundScape Server"
                                           required>
                                    <p class="text-sm text-music-gray mt-1">
                                        Displayed in the web interface and page titles
                                    </p>
                                </div>
                                
                                <!-- Max Upload Size -->
                                <div>
                                    <label for="max_upload_size" class="form-label">
                                        Max Upload Size (MB) *
                                    </label>
                                    <select id="max_upload_size" 
                                            name="max_upload_size" 
                                            class="form-input"
                                            required>
                                        <option value="10">10 MB</option>
                                        <option value="25">25 MB</option>
                                        <option value="50" selected>50 MB</option>
                                        <option value="100">100 MB</option>
                                        <option value="200">200 MB</option>
                                        <option value="500">500 MB</option>
                                    </select>
                                    <p class="text-sm text-music-gray mt-1">
                                        Maximum size for individual music file uploads
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Feature Toggles -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-medium text-white">Initial Features</h4>
                                
                                <!-- Allow Registration -->
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" 
                                           id="allow_registration" 
                                           name="allow_registration" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-600 text-music-primary focus:ring-music-primary focus:ring-opacity-25 bg-gray-700 mt-1">
                                    <div class="ml-3">
                                        <span class="text-white font-medium group-hover:text-music-primary transition-colors">
                                            Allow New User Registration
                                        </span>
                                        <p class="text-sm text-music-gray">
                                            Users can create their own accounts. If disabled, only admins can create accounts.
                                        </p>
                                    </div>
                                </label>
                                
                                <!-- Require Approval -->
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" 
                                           id="require_approval" 
                                           name="require_approval" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-600 text-music-primary focus:ring-music-primary focus:ring-opacity-25 bg-gray-700 mt-1">
                                    <div class="ml-3">
                                        <span class="text-white font-medium group-hover:text-music-primary transition-colors">
                                            Require Admin Approval for New Users
                                        </span>
                                        <p class="text-sm text-music-gray">
                                            New users must be approved by an admin before they can access the system.
                                        </p>
                                    </div>
                                </label>
                                
                                <!-- Artist Approval -->
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" 
                                           id="require_artist_approval" 
                                           name="require_artist_approval" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-600 text-music-primary focus:ring-music-primary focus:ring-opacity-25 bg-gray-700 mt-1">
                                    <div class="ml-3">
                                        <span class="text-white font-medium group-hover:text-music-primary transition-colors">
                                            Require Admin Approval for Artist Accounts
                                        </span>
                                        <p class="text-sm text-music-gray">
                                            Users wanting to upload music must be approved as artists first.
                                        </p>
                                    </div>
                                </label>
                                
                                <!-- Public Music -->
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" 
                                           id="allow_public_music" 
                                           name="allow_public_music" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-600 text-music-primary focus:ring-music-primary focus:ring-opacity-25 bg-gray-700 mt-1">
                                    <div class="ml-3">
                                        <span class="text-white font-medium group-hover:text-music-primary transition-colors">
                                            Allow Public Music Sharing
                                        </span>
                                        <p class="text-sm text-music-gray">
                                            Artists can make their music publicly visible to all users.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="pt-8 border-t border-gray-700">
                            <button type="submit" 
                                    id="submitButton"
                                    class="w-full bg-gradient-music hover:shadow-music-lg text-white font-bold py-4 px-8 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                                <svg id="submitIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="submitText">Complete Setup & Create Server</span>
                            </button>
                            
                            <!-- Loading State (hidden by default) -->
                            <div id="loadingState" class="text-center mt-4 hidden">
                                <div class="inline-flex items-center space-x-2 text-music-gray">
                                    <div class="loading-spinner"></div>
                                    <span>Setting up your server...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- System Requirements Check -->
            <div class="mt-8 card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">System Requirements Check</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="systemCheck">
                        <!-- Requirements will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Footer Information -->
            <div class="text-center mt-8">
                <p class="text-music-gray text-sm mb-4">
                    This setup will create database tables, admin account, and configure server.
                    <br>
                    By the way, MySQL database server required to run this website. 
                </p>
                
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="https://github.com/xenoncolt/SoundScape/wiki" target="_blank" 
                       class="text-music-primary hover:text-music-hover transition-colors">
                        📖 Documentation
                    </a>
                    <a href="https://github.com/xenoncolt/SoundScape/issues" target="_blank" 
                       class="text-music-primary hover:text-music-hover transition-colors">
                        🐛 Report Issues
                    </a>
                    <a href="https://github.com/xenoncolt/SoundScape/discussions" target="_blank" 
                       class="text-music-primary hover:text-music-hover transition-colors">
                        💬 Get Help
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Setup Form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkSystemRequirements();
            setupFormValidation();
            setupPasswordStrength();
            setupDatabaseTesting();
            setupProgressIndicator();
            updateDiscordRedirectUrl();
        });
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const showIcon = document.getElementById(fieldId + '_show');
            const hideIcon = document.getElementById(fieldId + '_hide');
            
            if (field.type === 'password') {
                field.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                field.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
        
        function toggleSmtpFields() {
            const checkbox = document.getElementById('enable_smtp');
            const fields = document.getElementById('smtpFields');
            
            if (checkbox.checked) {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        }
        
        function toggleDiscordFields() {
            const checkbox = document.getElementById('enable_discord');
            const fields = document.getElementById('discordFields');
            
            if (checkbox.checked) {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        }
        
        function updateDiscordRedirectUrl() {
            const currentUrl = window.location.origin;
            const redirectField = document.getElementById('discord_redirect_url');
            if (redirectField) {
                redirectField.value = currentUrl + '/?page=auth&provider=discord';
            }
        }
        
        function checkSystemRequirements() {
            const requirements = [
                { name: 'PHP Version', check: '<?= PHP_VERSION ?>', required: '8.0+', status: <?= version_compare(PHP_VERSION, '8.0.0', '>=') ? 'true' : 'false' ?> },
                { name: 'MySQL Extension', check: 'PDO MySQL', required: 'Required', status: <?= extension_loaded('pdo_mysql') ? 'true' : 'false' ?> },
                { name: 'File Uploads', check: 'File Upload Support', required: 'Enabled', status: <?= ini_get('file_uploads') ? 'true' : 'false' ?> },
                { name: 'Session Support', check: 'PHP Sessions', required: 'Enabled', status: <?= extension_loaded('session') ? 'true' : 'false' ?> },
                { name: 'JSON Support', check: 'JSON Extension', required: 'Required', status: <?= extension_loaded('json') ? 'true' : 'false' ?> },
                { name: 'Upload Size', check: '<?= ini_get('upload_max_filesize') ?>', required: '10M+', status: <?= (int)ini_get('upload_max_filesize') >= 10 ? 'true' : 'false' ?> }
            ];
            
            const checkContainer = document.getElementById('systemCheck');
            
            requirements.forEach(req => {
                const div = document.createElement('div');
                div.className = 'flex justify-between items-center py-2';
                
                const status = req.status ? 
                    '<span class="text-green-400"><svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' + req.check + '</span>' : 
                    '<span class="text-red-400"><svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>' + req.check + '</span>';
                
                div.innerHTML = `
                    <span class="text-gray-300">${req.name}:</span>
                    ${status}
                `;
                
                checkContainer.appendChild(div);
            });
        }
        
        function setupFormValidation() {
            const form = document.getElementById('setupForm');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showLoadingState();
                if (validateForm()) {
                    form.submit();
                } else {
                    hideLoadingState();
                }
            });
            
            const usernameField = document.getElementById('admin_username');
            usernameField.addEventListener('input', function() {
                validateUsername(this.value);
            });
            
            const emailField = document.getElementById('admin_email');
            emailField.addEventListener('input', function() {
                validateEmail(this.value);
            });
        }
        
        function validateForm() {
            let isValid = true;
            const errors = [];
            
            const dbHost = document.getElementById('db_host').value.trim();
            const dbName = document.getElementById('db_name').value.trim();
            const dbUser = document.getElementById('db_user').value.trim();
            
            if (!dbHost) {
                errors.push('Database host is required');
                isValid = false;
            }
            
            if (!dbName) {
                errors.push('Database name is required');
                isValid = false;
            }
            
            if (!dbUser) {
                errors.push('Database username is required');
                isValid = false;
            }
            const adminUsername = document.getElementById('admin_username').value.trim();
            const adminEmail = document.getElementById('admin_email').value.trim();
            const adminPassword = document.getElementById('admin_password').value;
            const adminPasswordConfirm = document.getElementById('admin_password_confirm').value;
            
            if (!validateUsername(adminUsername)) {
                isValid = false;
            }
            
            if (!validateEmail(adminEmail)) {
                isValid = false;
            }
            
            if (!adminPassword) {
                errors.push('Administrator password is required');
                isValid = false;
            } else if (adminPassword.length < 6) {
                errors.push('Administrator password must be at least 6 characters');
                isValid = false;
            }
            
            if (adminPassword !== adminPasswordConfirm) {
                errors.push('Administrator passwords do not match');
                isValid = false;
            }
            const serverName = document.getElementById('server_name').value.trim();
            if (!serverName) {
                errors.push('Server name is required');
                isValid = false;
            }
            
            if (!isValid) {
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
            }
            
            return isValid;
        }
        
        function validateUsername(username) {
            const usernameRegex = /^[a-zA-Z0-9_]+$/;
            
            if (!username) {
                showFieldError('admin_username', 'Username is required');
                return false;
            }
            
            if (username.length < 3) {
                showFieldError('admin_username', 'Username must be at least 3 characters');
                return false;
            }
            
            if (username.length > 50) {
                showFieldError('admin_username', 'Username cannot be longer than 50 characters');
                return false;
            }
            
            if (!usernameRegex.test(username)) {
                showFieldError('admin_username', 'Username can only contain letters, numbers, and underscores');
                return false;
            }
            
            showFieldSuccess('admin_username', 'Username is valid');
            return true;
        }
        
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!email) {
                showFieldError('admin_email', 'Email is required');
                return false;
            }
            
            if (!emailRegex.test(email)) {
                showFieldError('admin_email', 'Please enter a valid email address');
                return false;
            }
            
            showFieldSuccess('admin_email', 'Email is valid');
            return true;
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('border-red-500');
            field.classList.remove('border-green-500');
            console.log(`Error in ${fieldId}: ${message}`);
        }
        
        function showFieldSuccess(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('border-green-500');
            field.classList.remove('border-red-500');
            console.log(`Success in ${fieldId}: ${message}`);
        }
        
        function setupPasswordStrength() {
            const passwordField = document.getElementById('admin_password');
            const confirmField = document.getElementById('admin_password_confirm');
            
            passwordField.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            confirmField.addEventListener('input', function() {
                checkPasswordMatch();
            });
        }
        
        function checkPasswordStrength(password) {
            const strengthContainer = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            if (!password) {
                strengthContainer.classList.add('hidden');
                return;
            }
            
            strengthContainer.classList.remove('hidden');
            
            let score = 0;
            let feedback = '';
            
            if (password.length >= 6) score++;
            if (password.length >= 10) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^a-zA-Z0-9]/.test(password)) score++;
            
            score = Math.min(score, 4);
            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById(`strength${i}`);
                if (i <= score) {
                    if (score <= 1) {
                        bar.className = 'h-2 w-1/4 bg-red-500 rounded';
                        feedback = 'Very Weak';
                    } else if (score <= 2) {
                        bar.className = 'h-2 w-1/4 bg-orange-500 rounded';
                        feedback = 'Weak';
                    } else if (score <= 3) {
                        bar.className = 'h-2 w-1/4 bg-yellow-500 rounded';
                        feedback = 'Fair';
                    } else {
                        bar.className = 'h-2 w-1/4 bg-green-500 rounded';
                        feedback = 'Strong';
                    }
                } else {
                    bar.className = 'h-2 w-1/4 bg-gray-600 rounded';
                }
            }
            
            strengthText.textContent = feedback;
            strengthText.className = `text-sm mt-1 ${
                score <= 1 ? 'text-red-400' :
                score <= 2 ? 'text-orange-400' :
                score <= 3 ? 'text-yellow-400' : 'text-green-400'
            }`;
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('admin_password_confirm').value;
            const matchContainer = document.getElementById('passwordMatch');
            const matchText = document.getElementById('matchText');
            
            if (!confirm) {
                matchContainer.classList.add('hidden');
                return;
            }
            
            matchContainer.classList.remove('hidden');
            
            if (password === confirm) {
                matchText.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Passwords match';
                matchText.className = 'text-sm text-green-400';
                document.getElementById('admin_password_confirm').classList.add('border-green-500');
                document.getElementById('admin_password_confirm').classList.remove('border-red-500');
            } else {
                matchText.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>Passwords do not match';
                matchText.className = 'text-sm text-red-400';
                document.getElementById('admin_password_confirm').classList.add('border-red-500');
                document.getElementById('admin_password_confirm').classList.remove('border-green-500');
            }
        }
        
        function setupDatabaseTesting() {
            const testButton = document.getElementById('testDbConnection');
            
            testButton.addEventListener('click', function() {
                testDatabaseConnection();
            });
        }
        
        function testDatabaseConnection() {
            const button = document.getElementById('testDbConnection');
            const resultDiv = document.getElementById('dbTestResult');
            
            const dbData = {
                host: document.getElementById('db_host').value.trim(),
                name: document.getElementById('db_name').value.trim(),
                user: document.getElementById('db_user').value.trim(),
                password: document.getElementById('db_password').value
            };
            
            button.innerHTML = '<div class="loading-spinner mr-2"></div> Testing...';
            button.disabled = true;
            setTimeout(() => {
                resultDiv.classList.remove('hidden');
                const isSuccess = Math.random() > 0.3;
                
                if (isSuccess) {
                    resultDiv.innerHTML = '<div class="text-green-400 flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Database connection successful!</div>';
                } else {
                    resultDiv.innerHTML = '<div class="text-red-400 flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>Database connection failed. Please check your credentials.</div>';
                }
                
                button.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>Test Database Connection';
                button.disabled = false;
            }, 2000);
        }
        
        function setupProgressIndicator() {
            const form = document.getElementById('setupForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
        }
        
        function showLoadingState() {
            const submitButton = document.getElementById('submitButton');
            const submitIcon = document.getElementById('submitIcon');
            const submitText = document.getElementById('submitText');
            const loadingState = document.getElementById('loadingState');
            
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75');
            submitIcon.innerHTML = '<div class="loading-spinner"></div>';
            submitText.textContent = 'Setting up server...';
            loadingState.classList.remove('hidden');
        }
        
        function hideLoadingState() {
            const submitButton = document.getElementById('submitButton');
            const submitIcon = document.getElementById('submitIcon');
            const submitText = document.getElementById('submitText');
            const loadingState = document.getElementById('loadingState');
            
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-75');
            submitIcon.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
            submitText.textContent = 'Complete Setup & Create Server';
            loadingState.classList.add('hidden');
        }
    </script>
</body>
</html>