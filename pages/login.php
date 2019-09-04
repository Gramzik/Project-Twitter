<?php
//ta podstrona dostepna jest dla niezalogowanych

echo 'Logowanie' . '<br>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../src/User.php';

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //szukamy użytkownika po adresie email
    //jezeli istnieje to sprawdzamy czy haslo jest prawidlowe
    //jesli tak ustawiamy dane do sesji aby funkcja sprawdzajaca
    //czy ktos jest zalogowany zwraca nam true

    $user = User::loadUserByEmail($conn, $email);
    if (!is_null($user)) {
        if (password_verify($password, $user->getHashPass())) {
            $_SESSION['isLogged'] = true;
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ];

            ob_end_clean();
            header('Location: index.php');
        }
    }

}


?>

<form action="" method="post">
    <input type="text" name="email" placeholder="Email użytkownika">
    <br>
    <input type="password" name="password" placeholder="Hasło">
    <br>
    <br>
    <button>Zaloguj</button>
</form>