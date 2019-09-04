<?php
session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/configDB.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
</head>
<body>
<div>
    <a href="index.php?page=main">Strona główna</a>
    <a href="index.php?page=login">Logowanie</a>
    <a href="index.php?page=register">Rejestracja</a>
</div>
<br>
<div>
    <a href="index.php?page=account">Moje konto</a>
</div>
<br>
<br>
<div>
<?php
//zakladamy ze strony sa wybierane przez
//parametr GET -> page np.
//index.php?page=login
//index.php?page=twit
//index.php?page=../../../root/passwords.log
//itd.

if (isset($_GET['page']) && file_exists(__DIR__ . '/pages/' . $_GET['page'] . '.php') && strpos('..', $_GET['page']) === false) {
    include __DIR__ . '/pages/' . $_GET['page'] . '.php';
} else {
    include __DIR__ . '/pages/main.php';
}
?>
</div>
</body>
</html>