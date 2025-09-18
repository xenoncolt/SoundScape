<?php
$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();

if (!$isLoggedIn) {
    redirect('?page=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SoundScape</title>
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen pb-24 overflow-hidden">
    <div class="flex h-screen">
        <aside class="w-60 bg-black text-white fixed h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <img src="assets/images/SOUNDSCAPE.svg" alt="SoundScape" class="w-8 h-8">
                    <h1 class="text-xl font-bold">SoundScape</h1>
                </div>
                
                <nav class="space-y-1">
                    <a href="?page=dashboard" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-gray-800 text-white font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span>Home</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Search</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Your Library</span>
                    </a>
                </nav>
                
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wide">Library</h3>
                        <button class="text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <nav class="space-y-1">
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">Created Playlists</span>
                        </a>
                        
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">Liked Songs</span>
                        </a>
                        
                        <?php if ($currentUser['user_type'] === 'artist' || $currentUser['user_type'] === 'admin'): ?>
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm">Upload Music</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($currentUser['user_type'] === 'admin'): ?>
                            <a href="?page=discord-config" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm">Server Config</span>
                            </a>
                            
                            <a href="?page=user-management" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span class="text-sm">User Management</span>
                            </a>
                            
                            <a href="?page=pending-approvals" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">User Approvals</span>
                            </a>
                            
                            <a href="?page=music-approvals" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <span class="text-sm">Music Approvals</span>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
                
                <div class="mt-8 pt-4 border-t border-gray-800">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            <?php if ($currentUser['profile_image']): ?>
                                <img src="<?= htmlspecialchars($currentUser['profile_image']) ?>" alt="Profile" class="w-7 h-7 rounded-full">
                            <?php else: ?>
                                <div class="w-7 h-7 bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold"><?= strtoupper(substr($currentUser['display_name'] ?? 'U', 0, 1)) ?></span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-sm font-medium"><?= htmlspecialchars($currentUser['display_name']) ?></p>
                                <p class="text-xs text-gray-400"><?= ucfirst($currentUser['user_type']) ?></p>
                            </div>
                        </div>
                        <div class="relative group">
                            <button class="text-gray-400 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                            <div class="absolute bottom-full right-0 mb-2 w-40 bg-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-700">
                                <div class="py-2">
                                    <a href="?page=profile" class="block px-4 py-2 text-sm hover:bg-gray-700">Profile</a>
                                    <a href="?page=settings" class="block px-4 py-2 text-sm hover:bg-gray-700">Settings</a>
                                    <hr class="my-1 border-gray-700">
                                    <a href="?page=logout" class="block px-4 py-2 text-sm hover:bg-gray-700 text-red-400">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <main class="flex-1 ml-60 bg-gradient-to-b from-gray-900 to-black overflow-y-auto">
            <div class="p-8">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <?php 
                        $hour = (int)date('H');
                        if ($hour < 12) {
                            echo "Good morning";
                        } elseif ($hour < 17) {
                            echo "Good afternoon";
                        } else {
                            echo "Good evening";
                        }
                        ?>
                    </h1>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-purple-700 to-blue-900 p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-bold text-lg">Liked Songs</h3>
                                <p class="text-gray-200 text-sm">0 liked songs</p>
                            </div>
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-700 to-green-900 p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-bold text-lg">Recently Played</h3>
                                <p class="text-gray-200 text-sm">Your recent tracks</p>
                            </div>
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <?php if ($currentUser['user_type'] === 'artist' || $currentUser['user_type'] === 'admin'): ?>
                        <div class="bg-gradient-to-br from-orange-700 to-red-900 p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-lg">My Uploads</h3>
                                    <p class="text-gray-200 text-sm">Your music library</p>
                                </div>
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="bg-gradient-to-br from-pink-700 to-purple-900 p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-bold text-lg">Discover</h3>
                                <p class="text-gray-200 text-sm">New music for you</p>
                            </div>
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-white">Recently Played</h2>
                        <a href="#" class="text-sm text-gray-400 hover:text-white">Show all</a>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <div class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 transition-colors cursor-pointer group">
                                <div class="aspect-square bg-gray-600 rounded-lg mb-3 flex items-center justify-center relative overflow-hidden">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                    <button class="absolute bottom-2 right-2 bg-green-500 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                        <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <h3 class="text-white font-medium text-sm mb-1 truncate">No songs yet</h3>
                                <p class="text-gray-400 text-xs truncate">Start listening to music</p>
                            </div>
                        <?php endfor; ?>
                    </div>
                </section>

                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-white">Made for <?= htmlspecialchars($currentUser['display_name']) ?></h2>
                        <a href="#" class="text-sm text-gray-400 hover:text-white">Show all</a>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <div class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 transition-colors cursor-pointer group">
                                <div class="aspect-square bg-gray-600 rounded-lg mb-3 flex items-center justify-center relative overflow-hidden">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <button class="absolute bottom-2 right-2 bg-green-500 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                        <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <h3 class="text-white font-medium text-sm mb-1 truncate">Discover Weekly</h3>
                                <p class="text-gray-400 text-xs truncate">Your weekly mixtape of fresh music</p>
                            </div>
                        <?php endfor; ?>
                    </div>
                </section>

                <?php if ($currentUser['user_type'] === 'artist'): ?>
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-white">Your Music</h2>
                        <a href="#" class="text-sm text-gray-400 hover:text-white">Manage uploads</a>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-white mb-2">Share your music with the world</h3>
                        <p class="text-gray-400 mb-4">Upload your tracks and let listeners discover your sound</p>
                        <button class="bg-green-500 hover:bg-green-600 text-black font-bold py-2 px-6 rounded-full">
                            Upload Music
                        </button>
                    </div>
                </section>
                <?php elseif ($currentUser['user_type'] === 'admin'): ?>
                <!-- Admin Profile Section -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-white">Administrator Dashboard</h2>
                        <a href="?page=profile" class="text-sm text-cus-primary hover:text-white transition-colors">Edit Profile</a>
                    </div>
                    
                    <!-- Admin Profile Card -->
                    <div class="bg-gradient-to-r from-red-900/30 to-red-800/30 rounded-lg p-6 border border-red-500/30 mb-6">
                        <div class="flex items-center space-x-4">
                            <?php if ($currentUser['profile_image']): ?>
                                <img src="<?= htmlspecialchars($currentUser['profile_image']) ?>" alt="Profile" class="w-16 h-16 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    <?= strtoupper(substr($currentUser['display_name'] ?? $currentUser['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">
                                    <?= htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']) ?>
                                </h3>
                                <p class="text-red-300 text-sm">System Administrator</p>
                                <p class="text-gray-400 text-sm">@<?= htmlspecialchars($currentUser['username']) ?></p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600/20 text-red-300 border border-red-500">
                                        Administrator
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400">Full system access</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Admin Stats -->
                    <?php 
                    // Get quick admin stats
                    require_once __DIR__ . '/../Controllers/UserController.php';
                    require_once __DIR__ . '/../Controllers/MusicController.php';
                    
                    try {
                        $userController = new \App\Controllers\UserController();
                        $musicController = new \App\Controllers\MusicController();
                        
                        $userStats = $userController->getUserStats();
                        $musicStats = $musicController->getMusicStats();
                    } catch (Exception $e) {
                        $userStats = ['pending_approvals' => 0, 'total_users' => 0, 'recent_registrations' => 0];
                        $musicStats = ['pending_approvals' => 0, 'total_songs' => 0, 'recent_uploads' => 0];
                    }
                    ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-2xl font-bold text-white"><?= $userStats['pending_approvals'] ?></p>
                                    <p class="text-xs text-gray-400">User Approvals</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-2xl font-bold text-white"><?= $musicStats['pending_approvals'] ?></p>
                                    <p class="text-xs text-gray-400">Music Approvals</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-2xl font-bold text-white"><?= $userStats['total_users'] ?></p>
                                    <p class="text-xs text-gray-400">Total Users</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-2xl font-bold text-white"><?= $musicStats['total_songs'] ?></p>
                                    <p class="text-xs text-gray-400">Total Songs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Admin Management Tools -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-white">Management Tools</h2>
                        <a href="?page=user-management" class="text-sm text-gray-400 hover:text-white">View all</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="?page=pending-approvals" class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-yellow-400 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-white">User Approvals</h3>
                            </div>
                            <p class="text-gray-400 text-sm mb-4">Review pending user registrations and artist applications</p>
                            <p class="text-yellow-400 text-sm font-medium"><?= $userStats['pending_approvals'] ?> pending</p>
                        </a>
                        
                        <a href="?page=music-approvals" class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-purple-400 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-white">Music Approvals</h3>
                            </div>
                            <p class="text-gray-400 text-sm mb-4">Review and approve music uploads from artists</p>
                            <p class="text-purple-400 text-sm font-medium"><?= $musicStats['pending_approvals'] ?> pending</p>
                        </a>
                        
                        <a href="?page=discord-config" class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-green-400 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-white">Server Config</h3>
                            </div>
                            <p class="text-gray-400 text-sm mb-4">Manage Discord OAuth, SMTP and system settings</p>
                            <p class="text-green-400 text-sm font-medium">Configure system</p>
                        </a>
                    </div>
                </section>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <footer class="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 px-4 py-3 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 min-w-0 w-1/4">
                <div class="w-14 h-14 bg-gray-600 rounded flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-white text-sm font-medium truncate">No song playing</p>
                    <p class="text-gray-400 text-xs truncate">Select a song to play</p>
                </div>
                <button class="text-gray-400 hover:text-white p-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col items-center space-y-2 w-2/4">
                <div class="flex items-center space-x-4">
                    <button class="text-gray-400 hover:text-white p-2" title="Shuffle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v1m0 0v6m0-6H2m6 0V3m0 4h8M8 5v6m0 0v6m0-6h8m-8 0H4m12-6v6m0 0v6m0-6h4"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-white p-2" title="Previous">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 9H17a1 1 0 110 2h-5.586l4.293 4.293a1 1 0 010 1.414zM6 4a1 1 0 011 1v10a1 1 0 11-2 0V5a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button id="playPauseBtn" class="bg-white text-black rounded-full p-2 hover:scale-105 transition-transform" title="Play">
                        <svg id="playIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                        </svg>
                        <svg id="pauseIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-white p-2" title="Next">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L8.586 11H3a1 1 0 110-2h5.586L4.293 5.707a1 1 0 010-1.414zM14 4a1 1 0 011 1v10a1 1 0 11-2 0V5a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-white p-2" title="Repeat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-2 w-full max-w-lg">
                    <span class="text-xs text-gray-400 w-10 text-right">0:00</span>
                    <div class="flex-1 bg-gray-600 rounded-full h-1">
                        <div class="bg-white rounded-full h-1 w-0 transition-all duration-100"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-10">0:00</span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 w-1/4">
                <button class="text-gray-400 hover:text-white p-2" title="Queue">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </button>
                <button class="text-gray-400 hover:text-white p-2" title="Volume">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M9 5L5 9H1v6h4l4 4V5z"></path>
                    </svg>
                </button>
                <div class="w-20 bg-gray-600 rounded-full h-1">
                    <div class="bg-white rounded-full h-1 w-3/4"></div>
                </div>
                <button class="text-gray-400 hover:text-white p-2" title="Full Screen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </button>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded for user:', '<?= htmlspecialchars($currentUser['username']) ?>');
            
            const playPauseBtn = document.getElementById('playPauseBtn');
            const playIcon = document.getElementById('playIcon');
            const pauseIcon = document.getElementById('pauseIcon');
            let isPlaying = false;

            playPauseBtn.addEventListener('click', function() {
                isPlaying = !isPlaying;
                if (isPlaying) {
                    playIcon.classList.add('hidden');
                    pauseIcon.classList.remove('hidden');
                    playPauseBtn.title = 'Pause';
                } else {
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                    playPauseBtn.title = 'Play';
                }
            });
            
            document.querySelectorAll('a[href="#"], button').forEach(element => {
                if (!element.id || element.id !== 'playPauseBtn') {
                    element.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (element.textContent.includes('Upload') || element.textContent.includes('Server') || element.textContent.includes('Approve')) {
                            alert('This feature is coming soon!');
                        } else if (!element.getAttribute('href') || element.getAttribute('href') === '#') {
                            alert('This feature is coming soon!');
                        }
                    });
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    playPauseBtn.click();
                }
            });
        });
    </script>

    <style>
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }
        
        ::-webkit-scrollbar {
            width: 12px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 6px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</body>
</html>