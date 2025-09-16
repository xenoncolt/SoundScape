<?php
if (!defined('INCLUDED_FROM_ROUTER')) {
    http_response_code(403);
    exit('Direct access not allowed');
}

requiredLogin();
if ($_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    exit('Admin access required');
}

use App\Database\Connection;
$db = Connection::getInstance()->getConnection();

$success = $_SESSION['discord_success'] ?? null;
$error = $_SESSION['discord_error'] ?? null;
unset($_SESSION['discord_success'], $_SESSION['discord_error']);

$currentConfig = [];
$stmt = $db->prepare('SELECT key_name, key_value FROM config WHERE key_name LIKE "discord_%" ORDER BY key_name');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $currentConfig[$row['key_name']] = $row['key_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discord Configuration | SoundScape</title>
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-gray-900 min-h-screen">
    <div class="animated-bg">
        <div class="bg-shape shape1"></div>
        <div class="bg-shape shape2"></div>
        <div class="bg-shape shape3"></div>
        <div class="bg-shape shape4"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
        <div class="bg-cus-card backdrop-blur-sm border border-gray-700 rounded-2xl p-8 w-full max-w-2xl animate-slide-up">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Discord OAuth Configuration</h1>
                <p class="text-cus-gray">Configure Discord authentication for your SoundScape server</p>
            </div>

            <?php if ($success): ?>
                <div class="bg-green-900 bg-opacity-50 border border-green-600 rounded-lg p-4 mb-6 flex items-start">
                    <svg class="w-6 h-6 text-green-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-400 mb-1">Configuration Updated</h4>
                        <p class="text-green-300 text-sm"><?= htmlspecialchars($success) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-900 bg-opacity-50 border border-red-600 rounded-lg p-4 mb-6 flex items-start">
                    <svg class="w-6 h-6 text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-400 mb-1">Configuration Error</h4>
                        <p class="text-red-300 text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="?page=discord-config-save" class="space-y-6">
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="discord_enabled" 
                               value="1" 
                               <?= ($currentConfig['discord_enabled'] ?? '0') === '1' ? 'checked' : '' ?>
                               class="rounded border-gray-600 text-cus-primary focus:ring-cus-primary focus:ring-opacity-25 bg-gray-700">
                        <div class="ml-3">
                            <span class="text-white font-medium">Enable Discord OAuth</span>
                            <p class="text-sm text-cus-gray">Allow users to login and register using Discord</p>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="discord_client_id" class="form-label">
                            Discord Client ID
                        </label>
                        <input type="text" 
                               id="discord_client_id" 
                               name="discord_client_id" 
                               class="form-input"
                               value="<?= htmlspecialchars($currentConfig['discord_client_id'] ?? '') ?>"
                               placeholder="Your Discord application Client ID">
                        <p class="mt-1 text-xs text-cus-gray">Found in Discord Developer Portal > OAuth2 > General</p>
                    </div>

                    <div>
                        <label for="discord_client_secret" class="form-label">
                            Discord Client Secret
                        </label>
                        <input type="password" 
                               id="discord_client_secret" 
                               name="discord_client_secret" 
                               class="form-input"
                               value="<?= htmlspecialchars($currentConfig['discord_client_secret'] ?? '') ?>"
                               placeholder="Your Discord application Client Secret">
                        <p class="mt-1 text-xs text-cus-gray">Found in Discord Developer Portal > OAuth2 > General</p>
                    </div>

                    <div>
                        <label for="discord_redirect_uri" class="form-label">
                            Discord Redirect URI
                        </label>
                        <input type="url" 
                               id="discord_redirect_uri" 
                               name="discord_redirect_uri" 
                               class="form-input"
                               value="<?= htmlspecialchars($currentConfig['discord_redirect_uri'] ?? '') ?>"
                               placeholder="http://localhost:4040?page=discord-callback">
                        <p class="mt-1 text-xs text-cus-gray">Add this URL to your Discord app's OAuth2 redirects</p>
                    </div>
                </div>

                <div class="bg-blue-900 bg-opacity-30 border border-blue-600 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-400 mb-2">Setup Instructions</h4>
                    <ol class="text-sm text-blue-300 space-y-1 list-decimal list-inside">
                        <li>Go to <a href="https://discord.com/developers/applications" target="_blank" class="text-cus-primary hover:underline">Discord Developer Portal</a></li>
                        <li>Create a new application or select existing one</li>
                        <li>Navigate to OAuth2 section</li>
                        <li>Copy Client ID and Client Secret</li>
                        <li>Add your redirect URI to the OAuth2 redirects list</li>
                        <li>Enable required scopes: <code class="bg-gray-800 px-1 rounded">identify</code> and <code class="bg-gray-800 px-1 rounded">email</code></li>
                    </ol>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-cus hover:shadow-cus-lg text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Save Configuration</span>
                    </button>
                    
                    <a href="?page=dashboard" 
                       class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>