<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Not Found | SoundScape</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-cus-dark text-white min-h-screen flex flex-col items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-6xl mb-4">🚫</div>
        <h1 class="text-3xl font-bold mb-2">404 - Page Not Found</h1>
        <p class="text-cus-light-gray mb-6">
            The page you are searching for is lost in space. Maybe it moved or never existed.
        </p>
        <a href="?page=home"
           class="px-8 py-3 rounded-lg bg-gradient-cus font-semibold shadow-cus hover:shadow-cus-lg transition">
            Back Home
        </a>
    </div>
</body>
</html>