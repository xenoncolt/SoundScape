<?php
$error = $_SESSION['login_error'] ?? null;
$success = $_SESSION['login_success'] ?? null;
$redirect = $_GET['redirect'] ?? '';

unset($_SESSION['login_error'], $_SESSION['login_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn | SoundScape</title>
    
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta name="description" content="Log in to your SoundScape account">
</head>
<body class="bg-cus-dark text-white min-h-screen flex items-center justify-center">
    
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-4000"></div>
    </div>
    
    <div class="relative w-full max-w-md mx-auto px-4">
        
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4 shadow-cus">
                <img src="/assets/images/SOUNDSCAPE.svg" alt="Logo">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-cus-light-gray">Log in to your SoundScape account</p>
        </div>
        
        <div class="bg-cus-card border border-gray-700 rounded-xl shadow-2xl p-6 animate-slide-up">
            
            <?php if ($error): ?>
                <div class="mb-4 p-4 bg-red-900 bg-opacity-50 border border-red-500 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-red-400 mr-2">⚠️</span>
                        <p class="text-red-100 text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="mb-4 p-4 bg-green-900 bg-opacity-50 border border-green-500 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-green-400 mr-2">✅</span>
                        <p class="text-green-100 text-sm"><?= htmlspecialchars($success) ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="?page=dashboard<?= $redirect ? '&redirect=' . urlencode($redirect) : '' ?>" class="space-y-6">
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                        Username or Email
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cus-primary focus:border-transparent transition-all"
                           placeholder="Enter your username or email"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           required>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cus-primary focus:border-transparent transition-all pr-12"
                               placeholder="Enter your password"
                               required>
                        
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('password')">
                            <svg id="password_show" class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="password_hide" class="h-5 w-5 text-gray-400 hover:text-white transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="remember_me" 
                               value="1"
                               class="rounded border-gray-600 text-cus-primary focus:ring-cus-primary focus:ring-opacity-25 bg-gray-700">
                        <span class="ml-2 text-sm text-gray-300">Remember me</span>
                    </label>
                    
                    <a href="?page=forgot-password" 
                       class="text-sm text-cus-primary hover:text-cus-hover transition-colors">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" 
                        class="w-full bg-gradient-cus hover:shadow-cus-lg text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                    <span>Log In</span>
                </button>
                
            </form>
            <div class="mt-6 mb-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-cus-card text-gray-400">or continue with</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <button type="button" 
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-600 rounded-lg text-white bg-gray-700 hover:bg-gray-600 transition-colors"
                        onclick="loginWithDiscord()">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.19.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                    Continue with Discord
                </button>
            </div>
            
        </div>
        
        <div class="text-center mt-6 animate-slide-up">
            <p class="text-gray-400">
                Don't have an account? 
                <a href="?page=register" class="text-cus-primary hover:text-cus-hover font-medium transition-colors">
                    Sign up here
                </a>
            </p>
        </div>
        
        <div class="text-center mt-4">
            <a href="?page=home" 
               class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </a>
        </div>
        
    </div>
    
    <script>
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
        
        function loginWithDiscord() {
            alert('Discord login will be available after setup completion');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    const form = document.querySelector('form');
                    if (form) {
                        form.submit();
                    }
                }
            });
            
            const usernameField = document.getElementById('username');
            const passwordField = document.getElementById('password');
            
            usernameField.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-600');
                }
            });
            
            passwordField.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-600');
                }
            });
            
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                
                submitButton.innerHTML = '<div class="loading-spinner mr-2"></div> Signing in...';
                submitButton.disabled = true;
                
            });
        });
    </script>
    
    <style>
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #374151;
            border-top: 2px solid #1db954;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        input:focus {
            box-shadow: 0 0 0 3px rgba(29, 185, 84, 0.1);
        }
        
        .animate-slide-up:nth-child(1) { animation-delay: 0.1s; }
        .animate-slide-up:nth-child(2) { animation-delay: 0.2s; }
        .animate-slide-up:nth-child(3) { animation-delay: 0.3s; }
    </style>
</body>
</html>