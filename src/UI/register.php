<?php
$errors = $_SESSION['register_errors'] ?? null;
$success = $_SESSION['register_success'] ?? null;
$formData = $_SESSION['register_form_data'] ?? [];

unset($_SESSION['register_errors'], $_SESSION['register_success'], $_SESSION['register_form_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | SoundScape</title>
    
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta name="description" content="Create your SoundScape account">
</head>
<body class="bg-cus-dark text-white min-h-screen flex items-center justify-center">
    
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-4000"></div>
    </div>
    
    <div class="relative w-full max-w-2xl mx-auto px-4">
        
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4 shadow-cus">
                <img src="/assets/images/SOUNDSCAPE.svg" alt="Logo">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Join SoundScape</h1>
            <p class="text-cus-light-gray">Create your account and start your musical journey</p>
        </div>
        
        <div class="bg-cus-card border border-gray-700 rounded-xl shadow-2xl p-6 animate-slide-up">
            
            <?php if ($errors): ?>
                <div class="mb-6 p-4 bg-red-900 bg-opacity-50 border border-red-500 rounded-lg">
                    <div class="flex items-start">
                        <span class="text-red-400 mr-2 mt-0.5">⚠️</span>
                        <div class="text-red-100 text-sm">
                            <?= nl2br(htmlspecialchars($errors)) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-green-900 bg-opacity-50 border border-green-500 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-green-400 mr-2">✅</span>
                        <p class="text-green-100 text-sm"><?= htmlspecialchars($success) ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="?page=register" class="space-y-6">
                

                <div class="border-b border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-white mb-4">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        

                        <div>
                            <label for="username" class="form-label">
                                Username *
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="form-input"
                                   placeholder="Choose a unique username"
                                   value="<?= htmlspecialchars($formData['username'] ?? '') ?>"
                                   required
                                   minlength="3"
                                   maxlength="50"
                                   pattern="[a-zA-Z0-9_]+"
                                   title="Username can only contain letters, numbers, and underscores">
                            <p class="text-xs text-cus-gray mt-1">
                                3-50 characters, letters, numbers, and underscores only
                            </p>
                        </div>
                        

                        <div>
                            <label for="email" class="form-label">
                                Email Address *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input"
                                   placeholder="your.email@example.com"
                                   value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                                   required>
                            <p class="text-xs text-cus-gray mt-1">
                                Used for account recovery and notifications
                            </p>
                        </div>
                    </div>
                </div>
                

                <div class="border-b border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-white mb-4">Security</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        

                        <div>
                            <label for="password" class="form-label">
                                Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input pr-12"
                                       placeholder="Enter strong password"
                                       required
                                       minlength="6">
                                
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
                            

                            <div id="passwordStrength" class="mt-2 hidden">
                                <div class="flex space-x-1">
                                    <div id="strength1" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                    <div id="strength2" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                    <div id="strength3" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                    <div id="strength4" class="h-2 w-1/4 bg-gray-600 rounded"></div>
                                </div>
                                <p id="strengthText" class="text-xs mt-1"></p>
                            </div>
                        </div>
                        

                        <div>
                            <label for="password_confirm" class="form-label">
                                Confirm Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="password_confirm" 
                                       name="password_confirm" 
                                       class="form-input pr-12"
                                       placeholder="Confirm your password"
                                       required>
                                
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                        onclick="togglePassword('password_confirm')">
                                    <svg id="password_confirm_show" class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="password_confirm_hide" class="h-5 w-5 text-gray-400 hover:text-white transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878A3 3 0 0112 9c.21 0 .414.032.607.091m0 0a3 3 0 014.242 4.242M15.536 15.536L17.95 17.95M15.536 15.536A3 3 0 0112 15.91m0 0v.09a3 3 0 01-2.121-.879M15.536 15.536L12 12.01M3.05 3.05l17.9 17.9"></path>
                                    </svg>
                                </button>
                            </div>
                            

                            <div id="passwordMatch" class="mt-2 hidden">
                                <p id="matchText" class="text-xs"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="border-b border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-white mb-4">Account Type</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        

                        <label class="cursor-pointer">
                            <input type="radio" 
                                   name="user_type" 
                                   value="general" 
                                   <?= (!isset($formData['user_type']) || $formData['user_type'] === 'general') ? 'checked' : '' ?>
                                   class="sr-only peer">
                            <div class="border-2 border-gray-600 peer-checked:border-cus-primary peer-checked:bg-cus-primary peer-checked:bg-opacity-10 rounded-lg p-4 transition-all">
                                <div class="flex items-center mb-2">
                                    <span class="text-2xl mr-3">👤</span>
                                    <h4 class="font-semibold text-white">Music Lover</h4>
                                </div>
                                <p class="text-sm text-cus-gray">
                                    Listen to music, create playlists, and discover new tracks from artists.
                                </p>
                            </div>
                        </label>
                        

                        <label class="cursor-pointer">
                            <input type="radio" 
                                   name="user_type" 
                                   value="artist" 
                                   <?= (isset($formData['user_type']) && $formData['user_type'] === 'artist') ? 'checked' : '' ?>
                                   class="sr-only peer">
                            <div class="border-2 border-gray-600 peer-checked:border-cus-primary peer-checked:bg-cus-primary peer-checked:bg-opacity-10 rounded-lg p-4 transition-all">
                                <div class="flex items-center mb-2">
                                    <span class="text-2xl mr-3">🎤</span>
                                    <h4 class="font-semibold text-white">Artist</h4>
                                </div>
                                <p class="text-sm text-cus-gray">
                                    Upload your music, share with fans, and build your audience.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
                

                <div>
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" 
                               name="agree_terms" 
                               value="1" 
                               required
                               class="rounded border-gray-600 text-cus-primary focus:ring-cus-primary focus:ring-opacity-25 bg-gray-700 mt-1">
                        <div class="ml-3">
                            <span class="text-white text-sm">
                                I agree to the 
                                <a href="#" class="text-cus-primary hover:text-cus-hover transition-colors">Terms of Service</a> 
                                and 
                                <a href="#" class="text-cus-primary hover:text-cus-hover transition-colors">Privacy Policy</a>
                            </span>
                            <p class="text-xs text-cus-gray mt-1">
                                By creating an account, you agree to our terms and conditions.
                            </p>
                        </div>
                    </label>
                </div>
                

                <button type="submit" 
                        class="w-full bg-gradient-cus hover:shadow-cus-lg text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                    <span>🎵</span>
                    <span>Create Account</span>
                </button>
                
            </form>
            

            <div class="mt-6 mb-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-cus-card text-gray-400">or sign up with</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <button type="button" 
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-600 rounded-lg text-white bg-gray-700 hover:bg-gray-600 transition-colors"
                        onclick="signupWithDiscord()">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.19.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                    Continue with Discord
                </button>
            </div>
        </div>
        

        <div class="text-center mt-6 animate-slide-up">
            <p class="text-gray-400">
                Already have an account? 
                <a href="?page=login" class="text-cus-primary hover:text-cus-hover font-medium transition-colors">
                    Sign in here
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
        
        function signupWithDiscord() {
            alert('Discord signup will be available after setup completion');
        }
        
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        document.getElementById('password_confirm').addEventListener('input', function() {
            checkPasswordMatch();
        });
        
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
            strengthText.className = `text-xs mt-1 ${
                score <= 1 ? 'text-red-400' :
                score <= 2 ? 'text-orange-400' :
                score <= 3 ? 'text-yellow-400' : 'text-green-400'
            }`;
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const matchContainer = document.getElementById('passwordMatch');
            const matchText = document.getElementById('matchText');
            
            if (!confirm) {
                matchContainer.classList.add('hidden');
                return;
            }
            
            matchContainer.classList.remove('hidden');
            
            if (password === confirm) {
                matchText.textContent = 'Passwords match';
                matchText.className = 'text-xs text-green-400';
                document.getElementById('password_confirm').classList.add('border-green-500');
                document.getElementById('password_confirm').classList.remove('border-red-500');
            } else {
                matchText.textContent = 'Nice try...Passwords do not match';
                matchText.className = 'text-xs text-red-400';
                document.getElementById('password_confirm').classList.add('border-red-500');
                document.getElementById('password_confirm').classList.remove('border-green-500');
            }
        }
        
        // Form submission handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                
                submitButton.innerHTML = '<div class="loading-spinner mr-2"></div> Creating Account...';
                submitButton.disabled = true;
            });
        });
    </script>
</body>
</html>