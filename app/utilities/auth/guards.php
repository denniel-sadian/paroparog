<?php
require_once '/var/www/utilities/auth/session.php';


function allow($types) {
    if (isset($_SESSION['user'])) {
        $user = unserialize($_SESSION['user']);

        if ($user->password_changed == false) {
            exit(header('Location: /profile/update_password.php'));
        }

        //echo $user->type;
        //var_dump(!in_array($user->type, $types));
        if (!(in_array($user->type, $types))) {
            exit(header('Location: /forbidden.php'));
        }
    } else {
        exit(header('Location: /login.php'));
    }
}

function redirect() {
    if (isset($_SESSION['user'])) {
        $user = unserialize($_SESSION['user']);
        exit(header('Location: /ltpapplications/list.php'));
    }
}
