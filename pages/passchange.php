<?php

checkLoginOrRedirect();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    require_once __DIR__ . '/../src/User.php';

    $userId = $_SESSION['user']['id'];
    $oldPass = trim($_POST['oldPassword']);
    $newPass1 = trim($_POST['newPassword1']);
    $newPass2 = trim($_POST['newPassword2']);

    if ($newPass1 === $newPass2) {
        $users = User::loadUserById($conn, $userId);
        $userName = $users->getUsername();
        $userEmail = $users->getEmail();
        $hashPass = $users->getHashPass();
        if (password_verify($oldPass, $hashPass)) {
            $users->getId();
            $users->setHashPass($newPass1);
            $users->updatePassword($conn);
            echo 'Hasło zostało zmienione.';
        } else {
            echo 'Wpisano błędne stare hasło.';
        }
    } else {
        echo 'Nowe hasła są różne.';
    }

}
?>

<form action="" method="post">
    <div>
        <div>Stare hasło.</div>
        <input type="password" name="oldPassword" placeholder="Stare hasło">
        <div>Nowe hasło.</div>
        <input type="password" name="newPassword1" placeholder="Nowe hasło">
        <div>Powtórz hasło.</div>
        <input type="password" name="newPassword2" placeholder="Powtórz hasło">
        <br>
        <button>Zmień!</button>
    </div>
</form>