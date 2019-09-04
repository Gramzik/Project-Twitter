<?php
//ta podstrona dostepna jest dla niezalogowanych

echo 'Rejestracja'.'<br>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__.'/../src/User.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if (strlen($username) > 0) {
        if (strlen($email) > 0) {
            if ($password === $password2 && strlen($password) > 0) {
                //rejestrujemy użytkownika
                $user = new User();
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setHashPass($password);
                $user->saveToDB($conn);
                echo 'Zarejestrowano!';
            } else {
                echo 'Wpisano puste hasło lub hasła nie są identyczne';
            }
        } else {
            echo 'Wpisano pusty email';
        }
    } else {
        echo 'Wpisano pustą nazwę użytkownika';
    }
}

?>

<form action="" method="post">
    <input type="text" name="username" placeholder="Nazwa użytkownika">
    <br>
    <input type="text" name="email" placeholder="Email użytkownika">
    <br>
    <input type="password" name="password" placeholder="Hasło użytkownika">
    <br>
    <input type="password" name="password2" placeholder="Powtórz hasło">
    <br>
    <br>
    <button>Rejestruj</button>
</form>