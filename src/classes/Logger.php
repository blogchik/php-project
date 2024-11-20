<?php

namespace App\Classes;

class Logger
{
    private $logDirectory;

    public function __construct(string $logDirectory = __DIR__ . '/../../logs/')
    {
        $this->logDirectory = rtrim($logDirectory, '/') . '/';

        if (!is_dir($this->logDirectory)) {
            mkdir($this->logDirectory, 0775, true);
        }

        if (!is_writable($this->logDirectory)) {
            throw new \Exception("Permission denied: {$this->logDirectory}");
        }
    }

    public function log(string $message, string $type = 'info'): void
    {
        $filename = $this->logDirectory . $type . '.log';

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type]: $message" . PHP_EOL;

        if (file_put_contents($filename, $logMessage, FILE_APPEND) === false) {
            throw new \Exception("Error: {$filename}");
        }
    }

    public function error(string $errorMessage): void
    {
        $this->log($errorMessage, 'errors');
    }

    public function warning(string $warningMessage): void
    {
        $this->log($warningMessage, 'warnings');
    }

    public function info(string $infoMessage): void
    {
        $this->log($infoMessage, 'app');
    }

    public function readLog(string $type): array
    {
        $filename = $this->logDirectory . $type . '.log';

        if (!file_exists($filename)) {
            return [];
        }

        return file($filename, FILE_IGNORE_NEW_LINES);
    }
}