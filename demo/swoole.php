<?php

require_once __DIR__ .'/../vendor/autoload.php';

$config = include  __DIR__ . '/configs/swoole_console_demo.php';

$app = new \Commune\Chatbot\Framework\ChatApp($config);
$app->getServer()->run();

