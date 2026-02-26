<?php
session_start();

// Инициализация
if (!isset($_SESSION['пока_счетчик'])) {
    $_SESSION['пока_счетчик'] = 0;
    $_SESSION['диалог'] = ["ЧЕГО СКАЗАТЬ-ТО ХОТЕЛ, МИЛОК?!"];
}

// Обработка сообщения
if (isset($_POST['фраза'])) {
    $фраза = trim($_POST['фраза']);

    // Проверка на ПОКА!
    if ($фраза === "ПОКА!") {
        $_SESSION['пока_счетчик']++;
        if ($_SESSION['пока_счетчик'] >= 3) {
            $_SESSION['диалог'][] = "ДО СВИДАНИЯ, МИЛЫЙ!";
            session_destroy();
        } else {
            $год = rand(1930, 1950);
            $_SESSION['диалог'][] = "> $фраза";
            $_SESSION['диалог'][] = "НЕТ, НИ РАЗУ С $год ГОДА!";
        }
    }
    // Если фраза заканчивается на ! и в верхнем регистре
    elseif (strtoupper($фраза) === $фраза && substr($фраза, -1) === '!') {
        $_SESSION['пока_счетчик'] = 0;
        $год = rand(1930, 1950);
        $_SESSION['диалог'][] = "> $фраза";
        $_SESSION['диалог'][] = "НЕТ, НИ РАЗУ С $год ГОДА!";
    }
    // Если не крик
    else {
        $_SESSION['пока_счетчик'] = 0;
        $_SESSION['диалог'][] = "> $фраза";
        $_SESSION['диалог'][] = "АСЬ?! ГОВОРИ ГРОМЧЕ, ВНУЧЕК!";
    }
}

// HTML форма
?>
<!DOCTYPE html>
<html>
<head>
    <title>Глухая бабушка</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        .dialog { background: #f0f0f0; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .message { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Глухая бабушка</h1>

    <div class="dialog">
        <?php if (isset($_SESSION['диалог'])): ?>
            <?php foreach ($_SESSION['диалог'] as $сообщение): ?>
                <div class="message"><?php echo htmlspecialchars($сообщение); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!isset($_SESSION['пока_счетчик']) || $_SESSION['пока_счетчик'] < 3): ?>
        <form method="POST">
            <input type="text" name="фраза" placeholder="Введите фразу..." autofocus>
            <button type="submit">Отправить</button>
        </form>
    <?php else: ?>
        <a href="?reset=1">Начать заново</a>
    <?php endif; ?>

    <?php if (isset($_GET['reset'])): ?>
        <?php session_destroy(); header("Location: " . str_replace("?reset=1", "", $_SERVER['REQUEST_URI'])); ?>
    <?php endif; ?>
</body>
</html>