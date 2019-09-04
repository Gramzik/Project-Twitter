<?php

function isLogged()
{
    return isset($_SESSION['isLogged'], $_SESSION['user']) && ($_SESSION['isLogged']) === true;
}

function checkLoginOrRedirect()
{
    //sprawdzamy fak zalogowania uzytkownika
    if (!isLogged()) {
        //przekierowujemy
        ob_end_clean();
        header('Location: index.php?page=login');
        exit;
    }
}
