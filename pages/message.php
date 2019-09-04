<?php

checkLoginOrRedirect();
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Message.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['userId'] != $_SESSION['user']['id']) {
        $messageText = $_POST['text'];
        $theme = $_POST['theme'];
        $date = date('d-m-Y');
        $senderId = $_SESSION['user']['id'];
        $receiverId = $_GET['userId'];

        $message = new Message();
        $message->setText($messageText);
        $message->setCreationDate($date);
        $message->setReceiverId($receiverId);
        $message->setSenderId($senderId);
        $message->setTheme($theme);
        $message->saveToDB($conn);
        echo 'Wiadomość wysłana!<br><br>';
    } else {
        echo 'Nie można wysłać wiadomości do siebie.<br><br>';
    }
}

?>

Nowa Wiadomość.
<br>
<br>
<form action="" method="post">
    <div>
        <input type="text" name="theme" placeholder="Temat wiadomości"><br>
        <textarea name="text" placeholder="Tekst wiadomości"></textarea><br>
        <input type="submit" name="submit" placeholder="Wyślij!">
    </div>
</form>
