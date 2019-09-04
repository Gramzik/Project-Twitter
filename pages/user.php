<?php

checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Comment.php';

echo '<a href ="index.php?page=message&userId=' . $_GET['userId'] . '" >Wyślij wiadomość!</a><br><br>';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    $userId = $_GET['userId'];

    $tweets = Tweet::loadAllTweetsByUserId($conn, $userId);
    $user = User::loadUserById($conn, $userId);

    echo 'Tweety użytkownika ' . $user->getUsername() . '.<br><br>';

    foreach ($tweets as $tweet) {
        $postId = $tweet->getId();
        $userId = $tweet->getUserId();
        $text = $tweet->getText();
        $createDate = $tweet->getCreationDate();

        $comments = Comment::loadCommentsByPostIdWithUsers($conn, $postId);

        echo '<a href="index.php?page=tweet&userId=' . $userId . '&postId=' . $postId . '">' . $text . '</a><br>';
        echo $createDate . '<br><br>';
        echo 'Komentarze:' . '<br><br>';

        foreach ($comments as $comment) {
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
}