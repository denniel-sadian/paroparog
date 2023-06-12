<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/auth/session.php';
require_once '/var/www/utilities/auth/guards.php';

$context = array();
$context['tab'] = 'login';

if (isset($_POST['submit'])) {
    $user = login($_POST['username'], $_POST['password']);

    if ($user != null) {
        redirect();
    } else {
        $context['error'] = 'Ooops! You entered an invalid username or password.';
    }
}

echo $twig->render('login.html', $context);
