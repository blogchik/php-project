<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = require __DIR__ . '/../config/config.php';

if($config['app']['debug']){

    ini_set('error_reporting', 1);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

}else{

    error_reporting(0);
    ini_set('display_errors', '0');

}

require_once __DIR__ . '/../src/classes/Logger.php';
require_once __DIR__ . '/../src/classes/TelegramBot.php';

use App\Classes\Logger;
use App\Classes\TelegramBot;

require __DIR__ . '/../src/functions/database.php';

date_default_timezone_set($config['app']['timezone']);

$logger = new Logger();

$bot = new TelegramBot($_ENV['BOT_TOKEN'], $logger);

if($config['app']['debug'] AND isset($_GET['devMethod'])){

    $devMethod = $_GET['devMethod'];

    header('Content-Type: application/json');

    if($devMethod == "getme"){

        $response = $bot->getMe();
        echo json_encode($response, JSON_PRETTY_PRINT);
    
    }elseif($devMethod == "setwebhook"){

        $response = $bot->setWebhook($config['app']['app_url'], 50, false);
        echo json_encode($response, JSON_PRETTY_PRINT);

    }elseif($devMethod == "getwebhookinfo"){

        $response = $bot->getWebhookInfo();
        echo json_encode($response, JSON_PRETTY_PRINT);

    }elseif($devMethod == "deletewebhook"){

        $response = $bot->deleteWebhook();
        echo json_encode($response, JSON_PRETTY_PRINT);

    }

}

$update = json_decode(file_get_contents('php://input'));

if(isset($update)){

    if(isset($update->message)){

        $chat_id = $update->message->chat->id;

        $bot->callApi('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"Hello World!",
        ]);

    }

}

?>