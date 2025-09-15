<?php
// $message passed by SetupController->showError($message)
$message = $message ?? 'Unknown setup error';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Setup Error | SoundScape</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-cus-dark text-white min-h-screen flex items-center justify-center px-4">
    <div class="max-w-lg w-full bg-cus-card border border-red-600 rounded-2xl p-8">
        <div class="w-20 h-20 mx-auto rounded-full bg-red-600 flex items-center justify-center text-3xl">
            ⚠️
        </div>
        <h1 class="mt-6 text-3xl font-bold text-red-400">Setup Failed</h1>
        <pre class="mt-4 bg-gray-800 p-4 rounded text-sm text-red-200 whitespace-pre-wrap overflow-auto max-h-60"><?= htmlspecialchars($message) ?></pre>
        <p class="text-xs text-cus-light-gray mt-4">Fix the above issue and retry.</p>
        <div class="flex gap-4 justify-center mt-6">
            <a href="?page=setup" class="px-6 py-2 rounded bg-gradient-cus font-medium">Retry</a>
            <a href="https://github.com/xenoncolt/SoundScape/issues" target="_blank" class="px-6 py-2 rounded bg-gray-700 hover:bg-gray-600">Report</a>
        </div>
    </div>
</body>
</html>