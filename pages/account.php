<?php

checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
?>
    <div>
        <a href="index.php?page=passchange">Zmiana hasła.</a>
    </div>
    <div>
        <a href="index.php?page=userMessages">Wiadomości.</a>
    </div>
<?php
echo '
<div>

</div>'

?>