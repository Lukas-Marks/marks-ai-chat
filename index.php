<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Require
require __DIR__ . '/vendor/autoload.php';
session_start();

use Orhanerday\OpenAi\OpenAi;

// Lê .env manualmente
$env = parse_ini_file(__DIR__ . '/.env');
$open_ai_key = $env['OPENAI_API_KEY'];

$open_ai = new OpenAi($open_ai_key);

// cria históricoo
if (!isset($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

// RESET
if (isset($_POST['reset'])) {
    $_SESSION['chat'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// NOVA MENSAGEM
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['reset'])) {
    $pergunta = trim($_POST['pergunta'] ?? '');

    if (!empty($pergunta)) {

        // salva mensagem usuário
        $_SESSION['chat'][] = [
            'role' => 'user',
            'content' => $pergunta
        ];

        // envia histórico completo
        $chat = $open_ai->chat([
            'model' => 'gpt-4o-mini',
            'messages' => $_SESSION['chat'],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        $response = json_decode($chat, true);

        if (isset($response['choices'][0]['message']['content'])) {
            $resposta = $response['choices'][0]['message']['content'];

            // salva resposta IA
            $_SESSION['chat'][] = [
                'role' => 'assistant',
                'content' => $resposta
            ];
        } else {
            $_SESSION['chat'][] = [
                'role' => 'assistant',
                'content' => 'Erro ao conectar com a API.'
            ];
        }

        // evita repost no F5
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks AI Chat</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Inter, Arial, sans-serif;
        }

        body{
            background:#0f1115;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            color:white;
        }

        .chat-container{
            width:480px;
            height:680px;
            background:#16181d;
            border:1px solid #262a33;
            border-radius:20px;
            display:flex;
            flex-direction:column;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,.25);
        }

        .header{
            padding:18px;
            text-align:center;
            font-size:18px;
            font-weight:600;
            border-bottom:1px solid #262a33;
            color:#e5e7eb;
        }

        .chat-box{
            flex:1;
            padding:22px;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
            gap:14px;
        }

        .msg-user{
            align-self:flex-end;
            background:#2a2f3a;
            color:white;
            padding:12px 16px;
            border-radius:16px;
            max-width:80%;
            line-height:1.5;
            word-wrap:break-word;
        }

        .msg-ai{
            align-self:flex-start;
            background:#1d2128;
            border:1px solid #2d333d;
            color:#e5e7eb;
            padding:12px 16px;
            border-radius:16px;
            max-width:80%;
            line-height:1.6;
            word-wrap:break-word;
        }

        .input-box{
            display:flex;
            gap:10px;
            padding:16px;
            border-top:1px solid #262a33;
            background:#16181d;
        }

        input{
            flex:1;
            background:#1d2128;
            border:1px solid #2d333d;
            color:white;
            padding:14px 16px;
            border-radius:14px;
            outline:none;
            font-size:15px;
        }

        input:focus{
            border-color:#4b5563;
        }

        button{
            width:50px;
            height:50px;
            border:none;
            border-radius:14px;
            background:#2d333d;
            color:white;
            cursor:pointer;
            font-size:18px;
            transition:.2s;
        }

        button:hover{
            background:#3b4250;
        }

        .reset-btn{
            background:#20242c;
            font-size:16px;
        }

        .loader{
            display:none;
            align-self:flex-start;
            background:#1d2128;
            border:1px solid #2d333d;
            padding:14px 18px;
            border-radius:16px;
            width:90px;
        }

        .dots{
            display:flex;
            gap:6px;
            align-items:center;
        }

        .dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#9ca3af;
            animation:bounce 1.2s infinite;
        }

        .dot:nth-child(2){
            animation-delay:0.2s;
        }

        .dot:nth-child(3){
            animation-delay:0.4s;
        }

        @keyframes bounce{
            0%, 80%, 100%{
                transform:scale(0.8);
                opacity:.4;
            }
            40%{
                transform:scale(1.3);
                opacity:1;
            }
        }

        .status{
            font-size:12px;
            color:#9ca3af;
            margin-top:8px;
        }

        .empty-chat{
            color:#6b7280;
            text-align:center;
            margin-top:40px;
            font-size:14px;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="header">Marks AI Chat</div>

    <div class="chat-box" id="chatBox">

        <?php if (empty($_SESSION['chat'])): ?>
            <div class="empty-chat">
                Pergunte qualquer coisa...
            </div>
        <?php endif; ?>

        <?php foreach ($_SESSION['chat'] as $msg): ?>
            <div class="<?= $msg['role'] === 'user' ? 'msg-user' : 'msg-ai' ?>">
                <?= nl2br(htmlspecialchars($msg['content'])) ?>
            </div>
        <?php endforeach; ?>

        <div class="loader" id="loader">
            <div class="dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <div class="status">Pensando...</div>
        </div>
    </div>

    <form method="POST" class="input-box" id="chatForm">
        <input
            type="text"
            name="pergunta"
            placeholder="Pergunte qualquer coisa..."
            autocomplete="off"
            required
        >

        <button type="submit" id="sendBtn">➜</button>

        <button
            type="submit"
            name="reset"
            value="1"
            class="reset-btn"
        >
            ↺
        </button>
    </form>
</div>

<script>
    const form = document.getElementById('chatForm');
    const loader = document.getElementById('loader');
    const btn = document.getElementById('sendBtn');
    const chatBox = document.getElementById('chatBox');

    form.addEventListener('submit', (e) => {
        if (!e.submitter || e.submitter.name !== 'reset') {
            loader.style.display = 'block';
            btn.disabled = true;
            btn.innerHTML = '...';
        }
    });

    // scroll automático
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>