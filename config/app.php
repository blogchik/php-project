<?php

return [
    'app_name' => 'My Project',
    'app_url' => 'https://devme.uz/dev/telegram-bots/cardinalucbot/public/index.php',
    'environment' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'timezone' => 'Asia/Tashkent',
];