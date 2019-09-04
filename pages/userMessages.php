<?php

checkLoginOrRedirect();
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Message.php';

echo 'Wszystkie wiadomości:';

$messages = Message::LoadAllMessageByReceiverId($conn, $_SESSION['user']['id']);

foreach ($messages as $message) {
    $text = $message->getText();
    $senderId = $message->getSenderId();
    $theme = $message->getTheme();
    $date = $message->getCreationDate();

    $user = User::loadUserById($conn, $senderId);
    $userName = $user->getUsername();

    echo '
    <div>
        <a href="index.php">'. $userName .'</a>
    </div>
    ';
}
