<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = require __DIR__ . '/../config/config.php';

if ($config['app']['debug']) {
    ini_set('error_reporting', 1);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

require_once __DIR__ . '/../src/classes/Logger.php';

use App\Classes\Logger;

header('Content-Type: application/json');

try {
    $logger = new Logger();

    $logTypes = ['app', 'errors', 'warnings'];
    $allLogs = [];

    foreach ($logTypes as $type) {
        $logs = $logger->readLog($type);
        foreach ($logs as $log) {
            $allLogs[] = [
                'type' => $type,
                'message' => $log,
                'timestamp' => substr($log, 1, 19),
            ];
        }
    }

    usort($allLogs, function ($a, $b) {
        return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
    });

    echo json_encode(['logs' => $allLogs], JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
