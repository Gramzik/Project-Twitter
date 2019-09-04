<?php

//ta podstrona dostepna jest dla zalogowanych
checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/Comment.php';

echo 'Strona główna' . '<br><br>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['submit']) {
        case ('commentSend'):
            $commentText = $_POST['comment'];
            $date = date('d-m-Y');
            $postId = $_POST['postId'];
            $userId = $_SESSION['user']['id'];

            $comment = new Comment();
            $comment->setUserId($userId);
            $comment->setPostId($postId);
            $comment->setText($commentText);
            $comment->setCreateDate($date);
            $comment->saveToDB($conn);
            break;
        case ('tweet'):
            $text = $_POST['text'];
            $date = date('d-m-Y');
            $userId = $_SESSION['user']['id'];

            $tweet = new Tweet();
            $tweet->setText($text);
            $tweet->setCreationDate($date);
            $tweet->setUserId($userId);
            $tweet->saveToDB($conn);
    }
}
?>
<form action="" method="post">
    <div>Nowy Tweet</div>
    <div>
        <textarea type="text" name="text" cols="30" rows="10" placeholder="Stwórz nowego Tweet-a"></textarea><br>
        <button type="submit" name="submit" value="tweet">Wyślij</button>
        <br><br>
    </div>
</form>
<div>
    <?php
    echo 'Tweets.<br><br>';

    $tweets = Tweet::loadAllTweetsJoinUsers($conn);


    foreach ($tweets as $tweet) {
        $postId = $tweet->getId();
        $userId = $tweet->getUserId();
        $userName = $tweet->getUserName();
        $text = $tweet->getText();
        $creationDate = $tweet->getCreationDate();

        //wywołanie metody ładujacej commentsByPostId
        $comments = Comment::loadCommentsByPostIdWithUsers($conn, $postId);

        echo '<a href="index.php?page=user&userId=' . $userId . '">' . $userName . '<br></a>';
        echo '<a href="index.php?page=tweet&userId=' . $userId . '&postId=' . $postId . '">' . $text . '<br></a>';
        echo '<div>' . $creationDate . '</div><br>';
        echo 'Komentarze:' . '<br><br>';

        foreach ($comments as $comment){

            echo '<a href="index.php?page=user&userId=' . $comment->getUserId() . '">' . $comment->getUserName() . '<br></a>';
            echo $comment->getText() . ' ';
            echo $comment->getCreateDate();
            echo '<br>';
        }

        echo '
<form action="" method="post">
    <div>
    <input type="hidden" name="postId" value="' . $postId . '">
    <input type="text" name="comment" placeholder="Dodaj nowy komentarz.">
    <button type="submit" name="submit" value="commentSend">Wyślij</button>
    </div>
    <br>
</form>';
    }
    ?>
</div>