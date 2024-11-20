<?php

return [
    'host' => $_ENV['MYSQL_HOST'] ?? '127.0.0.1',
    'database' => $_ENV['MYSQL_DATABASE'] ?? 'my_database',
    'username' => $_ENV['MYSQL_USERNAME'] ?? 'root',
    'password' => $_ENV['MYSQL_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];