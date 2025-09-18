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

// Handle filters
$filters = [
    'status' => $_GET['status'] ?? '',
    'user_type' => $_GET['user_type'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$users = $userController->getAllUsers($filters);
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
    <title>User Management - SoundScape Admin</title>
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
                    <h1 class="text-2xl font-bold">User Management</h1>
                </div>
                <div class="text-sm text-gray-400">
                    Admin Panel
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-cus-primary/20 rounded-lg">
                        <svg class="w-6 h-6 text-cus-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Total Users</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['total_users'] ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Pending Approval</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['pending_approvals'] ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Artists</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['by_type']['artist'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">New This Month</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['recent_registrations'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-700 mb-8">
            <form method="GET" action="?page=user-management" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="page" value="user-management">
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-400 mb-2">Search Users</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="<?= htmlspecialchars($filters['search']) ?>"
                           placeholder="Username, email, or display name"
                           class="form-input w-full">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                    <select id="status" name="status" class="form-input w-full">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="banned" <?= $filters['status'] === 'banned' ? 'selected' : '' ?>>Banned</option>
                        <option value="suspended" <?= $filters['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                    </select>
                </div>

                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-400 mb-2">User Type</label>
                    <select id="user_type" name="user_type" class="form-input w-full">
                        <option value="">All Types</option>
                        <option value="admin" <?= $filters['user_type'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="artist" <?= $filters['user_type'] === 'artist' ? 'selected' : '' ?>>Artist</option>
                        <option value="general" <?= $filters['user_type'] === 'general' ? 'selected' : '' ?>>General</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Users (<?= count($users) ?>)</h2>
            </div>

            <?php if (empty($users)): ?>
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No users found matching your criteria</p>
                <a href="?page=user-management" class="text-cus-primary hover:text-white mt-2 inline-block">Clear filters</a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if ($user['profile_image']): ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile">
                                        <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-cus-primary to-blue-500 flex items-center justify-center text-white font-semibold text-lg">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">
                                            <?= htmlspecialchars($user['display_name'] ?: $user['username']) ?>
                                            <?php if ($user['has_discord']): ?>
                                            <svg class="inline w-4 h-4 ml-1 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.19.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                                            </svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            @<?= htmlspecialchars($user['username']) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php
                                    switch ($user['user_type']) {
                                        case 'admin': echo 'bg-red-600/20 text-red-300 border border-red-500'; break;
                                        case 'artist': echo 'bg-blue-600/20 text-blue-300 border border-blue-500'; break;
                                        case 'general': echo 'bg-gray-600/20 text-gray-300 border border-gray-500'; break;
                                    }
                                    ?>">
                                    <?= ucfirst($user['user_type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php
                                    switch ($user['status']) {
                                        case 'approved': echo 'bg-green-600/20 text-green-300 border border-green-500'; break;
                                        case 'pending': echo 'bg-yellow-600/20 text-yellow-300 border border-yellow-500'; break;
                                        case 'banned': echo 'bg-red-600/20 text-red-300 border border-red-500'; break;
                                        case 'suspended': echo 'bg-orange-600/20 text-orange-300 border border-orange-500'; break;
                                    }
                                    ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <?= date('M j, Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <?= $user['last_login_at'] ? date('M j, Y', strtotime($user['last_login_at'])) : 'Never' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Quick Actions for Pending Users -->
                                    <?php if ($user['status'] === 'pending'): ?>
                                    <form method="POST" action="?page=user-action" class="inline">
                                        <input type="hidden" name="action" value="approve-user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="text-green-400 hover:text-green-300 transition-colors" title="Approve User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="?page=user-action" class="inline">
                                        <input type="hidden" name="action" value="reject-user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Reject User" onclick="return confirm('Are you sure you want to reject this user? This will delete their account.')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                    <!-- Edit User Button -->
                                    <button onclick="openUserModal(<?= htmlspecialchars(json_encode($user)) ?>)" 
                                            class="text-cus-primary hover:text-white transition-colors" 
                                            title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- Delete User Button (not for admins or current user) -->
                                    <?php if ($user['user_type'] !== 'admin' && $user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" action="?page=user-action" class="inline">
                                        <input type="hidden" name="action" value="delete-user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete User" onclick="return confirm('Are you sure you want to delete this user account? This action cannot be undone.')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="userModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg max-w-md w-full p-6 border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Edit User</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editUserForm" method="POST" action="?page=user-action">
                    <input type="hidden" name="user_id" id="editUserId">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">User Type</label>
                            <select name="new_role" id="editUserType" class="form-input w-full">
                                <option value="general">General User</option>
                                <option value="artist">Artist</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                            <select name="new_status" id="editUserStatus" class="form-input w-full">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="suspended">Suspended</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <button type="submit" name="action" value="update-role" class="btn-primary flex-1">
                            Update Role
                        </button>
                        <button type="submit" name="action" value="update-status" class="btn-outline flex-1">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUserModal(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserType').value = user.user_type;
            document.getElementById('editUserStatus').value = user.status;
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // Close modal on background click
        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUserModal();
            }
        });
    </script>

</body>
</html>