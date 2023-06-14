<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';


$CONTEXT['tab'] = 'profile';

if (isset($_POST['submit']) && isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
    $password = $_POST['password'];
    $user->set_password($password);
    $user->password_changed = true;
    $user->save();

    $_SESSION['user'] = serialize($user);

    redirect();
}

echo $twig->render('update_password.html', $CONTEXT);
