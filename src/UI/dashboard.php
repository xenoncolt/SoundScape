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
<body class="bg-cus-dark text-white min-h-screen">
    
    <!-- Navigation Bar -->
    <nav class="bg-cus-sidebar border-b border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-cus rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">SoundScape</h1>
                        <p class="text-xs text-cus-gray">Dashboard</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-cus-gray">Welcome, <?= htmlspecialchars($currentUser['display_name']) ?>!</span>
                    
                    <div class="relative group">
                        <button class="flex items-center space-x-2 nav-item">
                            <div class="w-8 h-8 bg-cus-primary rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold">
                                    <?= strtoupper(substr($currentUser['display_name'] ?? 'U', 0, 1)) ?>
                                </span>
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-cus-card rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-700">
                            <div class="py-2">
                                <a href="?page=profile" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <a href="?page=settings" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>
                                <?php if ($currentUser['user_type'] === 'admin'): ?>
                                    <a href="?page=admin" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <hr class="my-1 border-gray-700">
                                <a href="?page=logout" class="block px-4 py-2 text-sm hover:bg-gray-700 text-red-400">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-cus-sidebar min-h-screen border-r border-gray-700">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="?page=dashboard" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-cus-primary text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0M8 11h.01M12 11h.01M16 11h.01"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                            <span>Browse Music</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>My Playlists</span>
                        </a>
                    </li>
                    <?php if ($currentUser['user_type'] === 'artist' || $currentUser['user_type'] === 'admin'): ?>
                        <li>
                            <a href="#" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span>Upload Music</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <span>My Music</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="#" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Favorites</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome back, <?= htmlspecialchars($currentUser['display_name']) ?>! 
                    <span class="text-2xl">🎉</span>
                </h1>
                <p class="text-cus-gray">
                    <?php 
                    $hour = (int)date('H');
                    if ($hour < 12) {
                        echo "Good morning! Ready to start your day with some music?";
                    } elseif ($hour < 17) {
                        echo "Good afternoon! How about some tunes to brighten your day?";
                    } else {
                        echo "Good evening! Time to unwind with your favorite tracks.";
                    }
                    ?>
                </p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Total Songs -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-cus-gray text-sm">Total Songs</p>
                                <p class="text-2xl font-bold text-white">0</p>
                            </div>
                            <div class="w-12 h-12 bg-cus-primary bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-cus-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Playlists -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-cus-gray text-sm">My Playlists</p>
                                <p class="text-2xl font-bold text-white">0</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Favorites -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-cus-gray text-sm">Favorites</p>
                                <p class="text-2xl font-bold text-white">0</p>
                            </div>
                            <div class="w-12 h-12 bg-red-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Listening Time -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-cus-gray text-sm">Hours Listened</p>
                                <p class="text-2xl font-bold text-white">0</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        <?php if ($currentUser['user_type'] === 'artist' || $currentUser['user_type'] === 'admin'): ?>
                            <a href="#" class="flex items-center space-x-3 p-4 border border-gray-600 rounded-lg hover:border-cus-primary hover:bg-cus-primary hover:bg-opacity-10 transition-all group">
                                <svg class="w-8 h-8 text-cus-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <div>
                                    <h4 class="text-white font-medium group-hover:text-cus-primary">Upload New Music</h4>
                                    <p class="text-sm text-cus-gray">Share your latest track</p>
                                </div>
                            </a>
                        <?php endif; ?>
                        
                        <a href="#" class="flex items-center space-x-3 p-4 border border-gray-600 rounded-lg hover:border-cus-primary hover:bg-cus-primary hover:bg-opacity-10 transition-all group">
                            <svg class="w-8 h-8 text-cus-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div>
                                <h4 class="text-white font-medium group-hover:text-cus-primary">Create Playlist</h4>
                                <p class="text-sm text-cus-gray">Organize your favorite songs</p>
                            </div>
                        </a>
                        
                        <a href="#" class="flex items-center space-x-3 p-4 border border-gray-600 rounded-lg hover:border-cus-primary hover:bg-cus-primary hover:bg-opacity-10 transition-all group">
                            <svg class="w-8 h-8 text-cus-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-white font-medium group-hover:text-cus-primary">Discover Music</h4>
                                <p class="text-sm text-cus-gray">Find new artists and genres</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps Information -->
            <div class="mt-8 p-6 bg-gray-800 rounded-lg border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Coming Soon
                </h3>
                <div class="text-sm text-cus-gray space-y-2">
                    <p>🎵 <strong>Music Upload:</strong> Upload and manage your music files</p>
                    <p>📋 <strong>Playlist Management:</strong> Create and organize playlists</p>
                    <p>🎧 <strong>Music Player:</strong> Built-in audio player with playlist support</p>
                    <p>🔍 <strong>Search & Discovery:</strong> Find music by title, artist, or genre</p>
                    <p>👥 <strong>User Management:</strong> Follow artists and share playlists</p>
                </div>
            </div>
            
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded for user:', '<?= htmlspecialchars($currentUser['username']) ?>');
            
            // Add some interactive features later
            document.querySelectorAll('a[href="#"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Show "coming soon" message
                    alert('This feature is coming soon! 🚧');
                });
            });
        });
    </script>
</body>
</html>