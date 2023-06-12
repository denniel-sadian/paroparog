<?php
require_once '/var/www/utilities/auth/session.php';


function allow($types) {
    if (isset($_SESSION['user'])) {
        $user = unserialize($_SESSION['user']);

        if ($user->password_changed == false) {
            exit(header('Location: /profile/update_password.php'));
        }

        if (!in_array($user->type, $types)) {
        exit(header('Location: /forbidden.php'));
        }
    } else {
        exit(header('Location: /login.php'));
    }
}

function redirect() {
    if (isset($_SESSION['user'])) {
        $user = unserialize($_SESSION['user']);

        if ($user->type == Models\UserType::CLIENT) {

        } elseif ($user->type == Models\UserType::ADMIN) {
            exit(header('Location: /admin/butterflies/list.php'));
        }
    }
}
