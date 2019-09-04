<?php

checkLoginOrRedirect();

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = $_POST['comment'];
    $date = date('d-m-Y');
    $postId = $_GET['postId'];
    $userId = $_SESSION['user']['id'];

    $comment = new Comment();
    $comment->setUserId($userId);
    $comment->setPostId($postId);
    $comment->setText($commentText);
    $comment->setCreateDate($date);
    $comment->saveToDB($conn);
}

$postId = $_GET['postId'];
$userId = $_GET['userId'];

$tweet = Tweet::loadTweetById($conn, $postId);
$user = User::loadUserById($conn, $userId);
$comments = Comment::loadCommentsByPostIdWithUsers($conn, $postId);


echo 'Tweet użytkownika ' . $user->getUsername() . '.<br><br>';
echo $tweet->getText() . '<br>';
echo $tweet->getCreationDate() . '<br><br>';
echo 'Komentarze: <br>';
foreach ($comments as $comment) {

    echo '<a href="index.php?page=user&userId=' . $comment->getUserId() . '">' . $comment->getUserName() . '<br></a>';
    echo $comment->getText() . ' ';
    echo $comment->getCreateDate();
    echo '<br>';
}
echo '
<form action="" method="post">
    <div>
    <input type="text" name="comment" placeholder="Dodaj nowy komentarz.">
    <button>Dodaj</button>
    </div>
    <br>
</form>';
