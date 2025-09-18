<?php
if (!defined('INCLUDED_FROM_ROUTER')) {
    die('Direct access not allowed');
}

// Ensure admin access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    redirect('?page=dashboard');
    return;
}

require_once __DIR__ . '/../Controllers/UserController.php';

use App\Controllers\UserController;

$userController = new UserController();

// Get pending users for approval
$pendingUsers = $userController->getAllUsers(['status' => 'pending']);
$stats = $userController->getUserStats();

// Get session messages
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals - SoundScape Admin</title>
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="bg-gradient-to-br from-gray-900 via-black to-gray-900 min-h-screen text-white">

    <!-- Background Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-red-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
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
                        <h1 class="text-2xl font-bold">Pending Approvals</h1>
                        <p class="text-sm text-gray-400">Review and approve new user registrations</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="?page=user-management" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </a>
                    <div class="text-sm text-gray-400">
                        Admin Panel
                    </div>
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

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-yellow-600/20 to-orange-600/20 backdrop-blur-sm rounded-lg p-6 border border-yellow-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-300">Pending Approvals</p>
                        <p class="text-3xl font-bold text-white"><?= count($pendingUsers) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm rounded-lg p-6 border border-blue-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-300">Artist Applications</p>
                        <p class="text-3xl font-bold text-white"><?= count(array_filter($pendingUsers, fn($u) => $u['user_type'] === 'artist')) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-600/20 to-emerald-600/20 backdrop-blur-sm rounded-lg p-6 border border-green-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-300">This Month</p>
                        <p class="text-3xl font-bold text-white"><?= $stats['recent_registrations'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($pendingUsers)): ?>
        <!-- No Pending Users -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">All caught up!</h3>
                <p class="text-gray-500 mb-6">No pending user registrations require approval at this time.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="?page=user-management" class="btn-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Manage All Users
                    </a>
                    <a href="?page=dashboard" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Bulk Actions -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold text-white">Pending Users (<?= count($pendingUsers) ?>)</h2>
                <p class="text-sm text-gray-400">Review new registrations and artist applications</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="selectAll()" class="text-sm text-gray-400 hover:text-white transition-colors">
                    Select All
                </button>
                <span class="text-gray-600">•</span>
                <button onclick="clearSelection()" class="text-sm text-gray-400 hover:text-white transition-colors">
                    Clear
                </button>
            </div>
        </div>

        <!-- Pending Users Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach ($pendingUsers as $user): ?>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 hover:border-gray-600 transition-all duration-200 p-6">
                <!-- User Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <?php if ($user['profile_image']): ?>
                            <img class="h-12 w-12 rounded-full object-cover" src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile">
                            <?php else: ?>
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-cus-primary to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-semibold text-white truncate">
                                <?= htmlspecialchars($user['display_name'] ?: $user['username']) ?>
                                <?php if ($user['has_discord']): ?>
                                <svg class="inline w-4 h-4 ml-1 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.19.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                                </svg>
                                <?php endif; ?>
                            </h3>
                            <p class="text-sm text-gray-400">@<?= htmlspecialchars($user['username']) ?></p>
                        </div>
                    </div>
                    
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        <?php
                        switch ($user['user_type']) {
                            case 'artist': echo 'bg-blue-600/20 text-blue-300 border border-blue-500'; break;
                            case 'general': echo 'bg-gray-600/20 text-gray-300 border border-gray-500'; break;
                            default: echo 'bg-gray-600/20 text-gray-300 border border-gray-500';
                        }
                        ?>">
                        <?= ucfirst($user['user_type']) ?>
                    </span>
                </div>

                <!-- User Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        <?= htmlspecialchars($user['email']) ?>
                    </div>
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Registered <?= date('M j, Y \a\t g:i A', strtotime($user['created_at'])) ?>
                    </div>

                    <?php if ($user['user_type'] === 'artist'): ?>
                    <div class="mt-3 p-3 bg-blue-600/10 border border-blue-500/30 rounded-lg">
                        <p class="text-sm text-blue-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                            <strong>Artist Application:</strong> This user wants to upload and share music with listeners.
                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <form method="POST" action="?page=user-action" class="flex-1">
                        <input type="hidden" name="action" value="approve-user">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="?page=user-action" class="flex-1">
                        <input type="hidden" name="action" value="reject-user">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center"
                                onclick="return confirm('Are you sure you want to reject this user? This will delete their account.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function selectAll() {
            // In a real implementation, you'd add checkboxes and bulk actions
            console.log('Select all functionality would go here');
        }

        function clearSelection() {
            // Clear all selections
            console.log('Clear selection functionality would go here');
        }
    </script>

</body>
</html>