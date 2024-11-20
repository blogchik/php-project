<?php

namespace App\Classes;

use App\Classes\Logger;

class TelegramBot
{
    private $botToken;
    private $logger;

    public function __construct(string $botToken, Logger $logger)
    {
        $this->botToken = $botToken;
        $this->logger = $logger;
    }

    public function callApi(string $method, array $datas = [])
    {
        $url = "https://api.telegram.org/bot" . $this->botToken . "/".$method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $this->logger->error('cURL Error: ' . curl_error($ch));
            return null;
        }

        return json_decode($response);
    }

    public function setWebhook(string $url, int $max_connections = 40, bool $drop_pending_updates = false)
    {
        $params = [
            'url' => $url,
            'max_connections' => $max_connections,
            'drop_pending_updates' => $drop_pending_updates ? 'true' : 'false',
        ];

        $response = $this->callApi('setWebhook', $params);

        if ($response && $response->ok) {
            $this->logger->info("Webhook successfully set to: $url");
        } else {
            $errorMessage = isset($response->description) ? $response->description : 'Unknown error';
            $this->logger->error("Failed to set webhook: " . $errorMessage);
            throw new \Exception("Failed to set webhook: " . $errorMessage);
        }

        return $response;
    }

    public function deleteWebhook()
    {
        $response = $this->callApi('deleteWebhook');

        if ($response && $response->ok) {
            $this->logger->info("Webhook deleted successfully.");
        } else {
            $this->logger->error("Failed to delete webhook: " . $response->description);
            throw new \Exception("Failed to delete webhook: " . $response->description);
        }

        return $response;
    }

    public function getWebhookInfo()
    {
        $response = $this->callApi('getWebhookInfo');

        if ($response && $response->ok) {
            $this->logger->info("Webhook info retrieved successfully.");
        } else {
            $this->logger->error("Failed to retrieve webhook info.");
            throw new \Exception("Failed to retrieve webhook info.");
        }

        return $response;
    }

    public function getMe()
    {
        $response = $this->callApi('getMe');

        if ($response && $response->ok) {
            $this->logger->info("GetMe info retrieved successfully.");
        } else {
            $this->logger->error("Failed to retrieve bot info.");
            throw new \Exception("Failed to retrieve bot info.");
        }

        return $response;
    }
}