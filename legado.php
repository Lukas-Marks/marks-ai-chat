<?php

// Esse foi o código base inicial


require __DIR__ . '/vendor/autoload.php';
session_start();

use Orhanerday\OpenAi\OpenAi;

$open_ai_key = $env['OPENAI_API_KEY'];
$open_ai = new OpenAi($open_ai_key);

$chat = $open_ai->chat([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Retorne um numero de 0 a 100']
    ],
    'temperature' => 1,
    'max_tokens' => 500,
]);

// transforma a resposta em array PHP
$response = json_decode($chat, true);

// pega só o texto da resposta
echo $response['choices'][0]['message']['content'];

?>
