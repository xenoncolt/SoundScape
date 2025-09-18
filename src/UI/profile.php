<?php
if (!defined('INCLUDED_FROM_ROUTER')) {
    die('Direct access not allowed');
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('?page=login');
    return;
}

require_once __DIR__ . '/../Controllers/UserProfileController.php';

use App\Controllers\UserProfileController;

$profileController = new UserProfileController();
$userProfile = $profileController->getCurrentUserProfile();
$userStats = $profileController->getUserStats($_SESSION['user_id']);

if (!$userProfile) {
    redirect('?page=login');
    return;
}

// Get session messages
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
$info = $_SESSION['info'] ?? null;
$profileErrors = $_SESSION['profile_errors'] ?? [];
$passwordErrors = $_SESSION['password_errors'] ?? [];

unset($_SESSION['success'], $_SESSION['error'], $_SESSION['info'], $_SESSION['profile_errors'], $_SESSION['password_errors']);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SoundScape</title>
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="bg-gradient-to-br from-gray-900 via-black to-gray-900 min-h-screen text-white">

    <!-- Background Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-cus-primary opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-purple-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation Header -->
    <header class="relative z-10 bg-gray-900/80 backdrop-blur-sm border-b border-gray-700">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="?page=dashboard" class="text-cus-primary hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">My Profile</h1>
                        <p class="text-sm text-gray-400">Manage your account settings and information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400">
                        <?= ucfirst($userProfile['user_type']) ?> Account
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="relative z-10 container mx-auto px-6 py-8">

        <!-- Alert Messages -->
        <?php if ($success): ?>
        <div class="mb-6 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="mb-6 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($info): ?>
        <div class="mb-6 bg-blue-600/20 border border-blue-500 text-blue-300 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($info) ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Profile Overview Card -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-6 sticky top-6">
                    
                    <!-- Avatar Section -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <?php if ($userProfile['profile_image']): ?>
                            <img class="w-24 h-24 rounded-full object-cover mx-auto mb-4" src="<?= htmlspecialchars($userProfile['profile_image']) ?>" alt="Profile">
                            <?php else: ?>
                            <div class="w-24 h-24 rounded-full bg-gradient-to-r from-cus-primary to-blue-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                                <?= strtoupper(substr($userProfile['display_name'] ?? $userProfile['username'], 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                            
                            <button onclick="openAvatarModal()" class="absolute bottom-0 right-0 bg-cus-primary hover:bg-green-600 text-white p-2 rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <h2 class="text-xl font-bold text-white mb-1">
                            <?= htmlspecialchars($userProfile['display_name'] ?: $userProfile['username']) ?>
                        </h2>
                        <p class="text-gray-400 text-sm mb-2">@<?= htmlspecialchars($userProfile['username']) ?></p>
                        
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php
                                switch ($userProfile['user_type']) {
                                    case 'admin': echo 'bg-red-600/20 text-red-300 border border-red-500'; break;
                                    case 'artist': echo 'bg-blue-600/20 text-blue-300 border border-blue-500'; break;
                                    case 'general': echo 'bg-gray-600/20 text-gray-300 border border-gray-500'; break;
                                }
                                ?>">
                                <?= ucfirst($userProfile['user_type']) ?>
                            </span>
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php
                                switch ($userProfile['status']) {
                                    case 'approved': echo 'bg-green-600/20 text-green-300 border border-green-500'; break;
                                    case 'pending': echo 'bg-yellow-600/20 text-yellow-300 border border-yellow-500'; break;
                                    case 'banned': echo 'bg-red-600/20 text-red-300 border border-red-500'; break;
                                }
                                ?>">
                                <?= ucfirst($userProfile['status']) ?>
                            </span>
                        </div>
                        
                        <?php if ($userProfile['bio']): ?>
                        <p class="text-gray-300 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($userProfile['bio'])) ?></p>
                        <?php else: ?>
                        <p class="text-gray-500 text-sm italic">No bio added yet</p>
                        <?php endif; ?>
                    </div>

                    <!-- Account Info -->
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <?= htmlspecialchars($userProfile['email']) ?>
                            <?php if ($userProfile['email_verified']): ?>
                            <svg class="w-4 h-4 ml-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l3 3 3-3"></path>
                            </svg>
                            Joined <?= date('M j, Y', strtotime($userProfile['created_at'])) ?>
                        </div>
                        
                        <?php if ($userProfile['discord_username']): ?>
                        <div class="flex items-center text-gray-400">
                            <svg class="w-4 h-4 mr-2 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.19.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                            </svg>
                            Connected as <?= htmlspecialchars($userProfile['discord_username']) ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($userProfile['last_login_at']): ?>
                        <div class="flex items-center text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Last seen <?= date('M j, Y', strtotime($userProfile['last_login_at'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Stats for Artists -->
                    <?php if ($userProfile['user_type'] === 'artist'): ?>
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <h3 class="text-sm font-medium text-gray-400 mb-3">Music Stats</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-white"><?= $userStats['total_songs'] ?></p>
                                <p class="text-xs text-gray-400">Songs</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-400"><?= $userStats['approved_songs'] ?></p>
                                <p class="text-xs text-gray-400">Approved</p>
                            </div>
                        </div>
                        <?php if ($userStats['pending_songs'] > 0): ?>
                        <div class="mt-3 text-center">
                            <p class="text-lg font-semibold text-yellow-400"><?= $userStats['pending_songs'] ?></p>
                            <p class="text-xs text-gray-400">Pending Approval</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Profile Information -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Profile Information</h3>
                    
                    <?php if (!empty($profileErrors)): ?>
                    <div class="mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
                        <?php foreach ($profileErrors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="?page=profile-action" class="space-y-4">
                        <input type="hidden" name="action" value="update-profile">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="display_name" class="form-label">Display Name *</label>
                                <input type="text" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="<?= htmlspecialchars($userProfile['display_name'] ?: $userProfile['username']) ?>"
                                       class="form-input"
                                       maxlength="100"
                                       required>
                            </div>
                            
                            <div>
                                <label for="username_display" class="form-label">Username</label>
                                <input type="text" 
                                       id="username_display" 
                                       value="<?= htmlspecialchars($userProfile['username']) ?>"
                                       class="form-input bg-gray-700 cursor-not-allowed"
                                       disabled>
                                <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                            </div>
                        </div>
                        
                        <div>
                            <label for="bio" class="form-label">Bio</label>
                            <textarea id="bio" 
                                      name="bio" 
                                      rows="4" 
                                      class="form-input"
                                      maxlength="500"
                                      placeholder="Tell us about yourself..."><?= htmlspecialchars($userProfile['bio'] ?? '') ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_private" 
                                   name="is_private" 
                                   <?= $userProfile['is_private'] ? 'checked' : '' ?>
                                   class="mr-2">
                            <label for="is_private" class="text-sm text-gray-300">Make my profile private</label>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Change Password</h3>
                    
                    <?php if (!empty($passwordErrors)): ?>
                    <div class="mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
                        <?php foreach ($passwordErrors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="?page=profile-action" class="space-y-4">
                        <input type="hidden" name="action" value="update-password">
                        
                        <div>
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="form-input"
                                   required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="new_password" class="form-label">New Password *</label>
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password" 
                                       class="form-input"
                                       minlength="6"
                                       required>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="form-label">Confirm New Password *</label>
                                <input type="password" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       class="form-input"
                                       minlength="6"
                                       required>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Information -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Account Information</h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Email Address</label>
                                <div class="flex items-center">
                                    <input type="email" 
                                           value="<?= htmlspecialchars($userProfile['email']) ?>"
                                           class="form-input bg-gray-700 cursor-not-allowed flex-1"
                                           disabled>
                                    <?php if ($userProfile['email_verified']): ?>
                                    <svg class="w-5 h-5 ml-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <?php else: ?>
                                    <span class="ml-2 text-xs text-yellow-400">Unverified</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Contact admin to change email</p>
                            </div>
                            
                            <div>
                                <label class="form-label">Account Type</label>
                                <input type="text" 
                                       value="<?= ucfirst($userProfile['user_type']) ?>"
                                       class="form-input bg-gray-700 cursor-not-allowed"
                                       disabled>
                                <p class="text-xs text-gray-500 mt-1">Role assigned by admin</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Account Status</label>
                                <input type="text" 
                                       value="<?= ucfirst($userProfile['status']) ?>"
                                       class="form-input bg-gray-700 cursor-not-allowed"
                                       disabled>
                            </div>
                            
                            <div>
                                <label class="form-label">Member Since</label>
                                <input type="text" 
                                       value="<?= date('F j, Y', strtotime($userProfile['created_at'])) ?>"
                                       class="form-input bg-gray-700 cursor-not-allowed"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Avatar Upload Modal -->
    <div id="avatarModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg max-w-md w-full p-6 border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Update Avatar</h3>
                    <button onclick="closeAvatarModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <form method="POST" action="?page=profile-action" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload-avatar">
                        
                        <div class="text-center mb-4">
                            <p class="text-gray-400 text-sm">Upload a new avatar image</p>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">Coming soon: Avatar upload</p>
                        </div>
                        
                        <?php if ($userProfile['profile_image']): ?>
                        <button type="submit" name="action" value="delete-avatar" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors mt-4">
                            Remove Current Avatar
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
        }

        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
        }

        // Close modal on background click
        document.getElementById('avatarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAvatarModal();
            }
        });
    </script>

</body>
</html>