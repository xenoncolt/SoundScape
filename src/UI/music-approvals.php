<?php
if (!defined('INCLUDED_FROM_ROUTER')) {
    die('Direct access not allowed');
}

// Ensure admin access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    redirect('?page=dashboard');
    return;
}

require_once __DIR__ . '/../Controllers/MusicController.php';

use App\Controllers\MusicController;

$musicController = new MusicController();

// Get pending songs for approval
$pendingSongs = $musicController->getPendingSongs();
$stats = $musicController->getMusicStats();

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
    <title>Music Approvals - SoundScape Admin</title>
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="bg-gradient-to-br from-gray-900 via-black to-gray-900 min-h-screen text-white">

    <!-- Background Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-pink-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-indigo-500 opacity-10 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
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
                        <h1 class="text-2xl font-bold">Music Approvals</h1>
                        <p class="text-sm text-gray-400">Review and approve music uploads from artists</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="?page=pending-approvals" class="text-gray-400 hover:text-white transition-colors" title="User Approvals">
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 backdrop-blur-sm rounded-lg p-6 border border-purple-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-300">Pending Approvals</p>
                        <p class="text-3xl font-bold text-white"><?= count($pendingSongs) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600/20 to-cyan-600/20 backdrop-blur-sm rounded-lg p-6 border border-blue-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-300">Approved Songs</p>
                        <p class="text-3xl font-bold text-white"><?= $stats['by_status']['approved'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-600/20 to-emerald-600/20 backdrop-blur-sm rounded-lg p-6 border border-green-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m3 0V1a1 1 0 00-1-1H9a1 1 0 00-1 1v3M5 7h10l1 12H4l1-12z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-300">Total Uploads</p>
                        <p class="text-3xl font-bold text-white"><?= $stats['total_songs'] ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-600/20 to-red-600/20 backdrop-blur-sm rounded-lg p-6 border border-orange-500/30">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-orange-300">This Month</p>
                        <p class="text-3xl font-bold text-white"><?= $stats['recent_uploads'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($pendingSongs)): ?>
        <!-- No Pending Songs -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No pending music uploads!</h3>
                <p class="text-gray-500 mb-6">All music uploads have been reviewed. Check back later for new submissions from artists.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="?page=pending-approvals" class="btn-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        User Approvals
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
        
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-semibold text-white">Pending Music (<?= count($pendingSongs) ?>)</h2>
                <p class="text-sm text-gray-400">Review new music uploads from artists</p>
            </div>
        </div>

        <!-- Pending Songs -->
        <div class="space-y-6">
            <?php foreach ($pendingSongs as $song): ?>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 hover:border-gray-600 transition-all duration-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                    
                    <!-- Song Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-1">
                                    <?= htmlspecialchars($song['title']) ?>
                                </h3>
                                <div class="flex items-center space-x-2 text-gray-400 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>by <?= htmlspecialchars($song['artist_display_name'] ?: $song['artist_name']) ?></span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-sm text-gray-400">Uploaded</p>
                                <p class="text-sm text-white"><?= date('M j, Y', strtotime($song['upload_date'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('g:i A', strtotime($song['upload_date'])) ?></p>
                            </div>
                        </div>

                        <!-- Song Details -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <?php if ($song['album']): ?>
                            <div>
                                <p class="text-gray-400">Album</p>
                                <p class="text-white"><?= htmlspecialchars($song['album']) ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($song['genre']): ?>
                            <div>
                                <p class="text-gray-400">Genre</p>
                                <p class="text-white"><?= htmlspecialchars($song['genre']) ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($song['year']): ?>
                            <div>
                                <p class="text-gray-400">Year</p>
                                <p class="text-white"><?= $song['year'] ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div>
                                <p class="text-gray-400">Duration</p>
                                <p class="text-white"><?= gmdate('i:s', $song['duration']) ?></p>
                            </div>
                        </div>

                        <!-- File Info -->
                        <div class="mt-4 p-3 bg-gray-700/30 rounded-lg">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-400">File Type</p>
                                    <p class="text-white"><?= htmlspecialchars($song['mime_type']) ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-400">File Size</p>
                                    <p class="text-white"><?= round($song['file_size'] / 1024 / 1024, 1) ?> MB</p>
                                </div>
                                <?php if ($song['bitrate']): ?>
                                <div>
                                    <p class="text-gray-400">Bitrate</p>
                                    <p class="text-white"><?= $song['bitrate'] ?> kbps</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-3 lg:w-48">
                        <!-- Preview Button (placeholder) -->
                        <button class="btn-outline flex items-center justify-center" disabled>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1"></path>
                            </svg>
                            Preview
                        </button>

                        <!-- Approve Button -->
                        <form method="POST" action="?page=music-action">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="song_id" value="<?= $song['id'] ?>">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <form method="POST" action="?page=music-action">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="song_id" value="<?= $song['id'] ?>">
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center"
                                    onclick="return confirm('Are you sure you want to reject this song?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</body>
</html>