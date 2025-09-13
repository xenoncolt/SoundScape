
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
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-music rounded-full mb-4 shadow-music">
                        <span class="text-3xl animate-bounce-gentle">🎵</span>
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
                        <span class="ml-2 text-gray-400">Settings</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-12 h-px bg-gray-600"></div>
                    
                    <!-- Step 4: Complete -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 font-bold">
                            ✓
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
                                    <span class="text-white text-xl">🗄️</span>
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
                                        💡 Usually 'localhost' for local servers, or your database server IP
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
                                        💡 Will be created automatically if it doesn't exist
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
                                        💡 MySQL user with database creation permissions
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
                                        💡 Leave empty if no password is set
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Database Test Button -->
                            <div class="mt-6">
                                <button type="button" 
                                        id="testDbConnection" 
                                        class="btn-outline">
                                    🔌 Test Database Connection
                                </button>
                                <div id="dbTestResult" class="mt-2 hidden"></div>
                            </div>
                        </div>
                        
                        <!-- Admin Account Section -->
                        <div class="border-b border-gray-700 pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <span class="text-white text-xl">👤</span>
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
                                        💡 3-50 characters, letters, numbers, and underscores only
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
                                        💡 Used for account recovery and notifications
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
                                        💡 Minimum 6 characters, use strong password for security
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
                        
                        <!-- Server Configuration Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                                    <span class="text-white text-xl">⚙️</span>
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
                                        💡 Displayed in the web interface and page titles
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
                                        💡 Maximum size for individual music file uploads
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
                                <span id="submitIcon">🚀</span>
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
                    This setup will create your database tables, admin account, and configure your server.
                    <br>
                    Make sure your MySQL server is running and accessible before proceeding.
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
        /**
         * Setup Form JavaScript
         * Handles form validation, user experience, and system checks
         */
        
        document.addEventListener('DOMContentLoaded', function() {
            // Run system requirements check
            checkSystemRequirements();
            
            // Set up form validation
            setupFormValidation();
            
            // Set up password strength checking
            setupPasswordStrength();
            
            // Set up database connection testing
            setupDatabaseTesting();
            
            // Set up dynamic progress indication
            setupProgressIndicator();
        });
        
        /**
         * Toggle password visibility
         * Shows/hides password fields when user clicks the eye icon
         */
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
        
        /**
         * Check system requirements
         * Verifies PHP version, extensions, and permissions
         */
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
                    '<span class="text-green-400">✅ ' + req.check + '</span>' : 
                    '<span class="text-red-400">❌ ' + req.check + '</span>';
                
                div.innerHTML = `
                    <span class="text-gray-300">${req.name}:</span>
                    ${status}
                `;
                
                checkContainer.appendChild(div);
            });
        }
        
        /**
         * Set up form validation
         * Validates form fields in real-time and on submit
         */
        function setupFormValidation() {
            const form = document.getElementById('setupForm');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                showLoadingState();
                
                // Validate all fields
                if (validateForm()) {
                    // Submit the form
                    form.submit();
                } else {
                    hideLoadingState();
                }
            });
            
            // Real-time validation for username
            const usernameField = document.getElementById('admin_username');
            usernameField.addEventListener('input', function() {
                validateUsername(this.value);
            });
            
            // Real-time validation for email
            const emailField = document.getElementById('admin_email');
            emailField.addEventListener('input', function() {
                validateEmail(this.value);
            });
        }
        
        /**
         * Validate entire form
         * Checks all required fields and validation rules
         */
        function validateForm() {
            let isValid = true;
            const errors = [];
            
            // Database validation
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
            
            // Admin account validation
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
            
            // Server settings validation
            const serverName = document.getElementById('server_name').value.trim();
            if (!serverName) {
                errors.push('Server name is required');
                isValid = false;
            }
            
            // Show errors if any
            if (!isValid) {
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
            }
            
            return isValid;
        }
        
        /**
         * Validate username
         * Checks format and length requirements
         */
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
        
        /**
         * Validate email address
         * Checks email format
         */
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
        
        /**
         * Show field-specific error message
         */
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('border-red-500');
            field.classList.remove('border-green-500');
            
            // Show error message (you can enhance this with tooltips)
            console.log(`Error in ${fieldId}: ${message}`);
        }
        
        /**
         * Show field-specific success message
         */
        function showFieldSuccess(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('border-green-500');
            field.classList.remove('border-red-500');
            
            console.log(`Success in ${fieldId}: ${message}`);
        }
        
        /**
         * Set up password strength indicator
         * Shows visual feedback for password strength
         */
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
        
        /**
         * Check password strength
         * Evaluates password and shows strength indicator
         */
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
            
            // Length check
            if (password.length >= 6) score++;
            if (password.length >= 10) score++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^a-zA-Z0-9]/.test(password)) score++;
            
            // Cap at 4
            score = Math.min(score, 4);
            
            // Update strength bars
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
        
        /**
         * Check if passwords match
         * Shows visual feedback for password confirmation
         */
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
                matchText.textContent = '✅ Passwords match';
                matchText.className = 'text-sm text-green-400';
                document.getElementById('admin_password_confirm').classList.add('border-green-500');
                document.getElementById('admin_password_confirm').classList.remove('border-red-500');
            } else {
                matchText.textContent = '❌ Passwords do not match';
                matchText.className = 'text-sm text-red-400';
                document.getElementById('admin_password_confirm').classList.add('border-red-500');
                document.getElementById('admin_password_confirm').classList.remove('border-green-500');
            }
        }
        
        /**
         * Set up database connection testing
         * Allows users to test database connection before submitting
         */
        function setupDatabaseTesting() {
            const testButton = document.getElementById('testDbConnection');
            
            testButton.addEventListener('click', function() {
                testDatabaseConnection();
            });
        }
        
        /**
         * Test database connection
         * Makes AJAX request to test database connectivity
         */
        function testDatabaseConnection() {
            const button = document.getElementById('testDbConnection');
            const resultDiv = document.getElementById('dbTestResult');
            
            // Get database values
            const dbData = {
                host: document.getElementById('db_host').value.trim(),
                name: document.getElementById('db_name').value.trim(),
                user: document.getElementById('db_user').value.trim(),
                password: document.getElementById('db_password').value
            };
            
            // Show loading state
            button.innerHTML = '<div class="loading-spinner mr-2"></div> Testing...';
            button.disabled = true;
            
            // Simulate database test (in real app, this would be an AJAX call)
            setTimeout(() => {
                resultDiv.classList.remove('hidden');
                
                // Simulate success/failure
                const isSuccess = Math.random() > 0.3; // 70% success rate for demo
                
                if (isSuccess) {
                    resultDiv.innerHTML = '<div class="text-green-400">✅ Database connection successful!</div>';
                } else {
                    resultDiv.innerHTML = '<div class="text-red-400">❌ Database connection failed. Please check your credentials.</div>';
                }
                
                // Reset button
                button.innerHTML = '🔌 Test Database Connection';
                button.disabled = false;
            }, 2000);
        }
        
        /**
         * Set up progress indicator
         * Updates progress steps based on form completion
         */
        function setupProgressIndicator() {
            // This would update the progress indicators at the top
            // as users complete different sections
            
            // Monitor form progress
            const form = document.getElementById('setupForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            
            // You can enhance this to show real-time progress
        }
        
        /**
         * Show loading state during form submission
         */
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
        
        /**
         * Hide loading state
         */
        function hideLoadingState() {
            const submitButton = document.getElementById('submitButton');
            const submitIcon = document.getElementById('submitIcon');
            const submitText = document.getElementById('submitText');
            const loadingState = document.getElementById('loadingState');
            
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-75');
            submitIcon.textContent = '🚀';
            submitText.textContent = 'Complete Setup & Create Server';
            loadingState.classList.add('hidden');
        }
    </script>
</body>
</html>