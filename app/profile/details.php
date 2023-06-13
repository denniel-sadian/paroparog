<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/files/uploader.php';

use Models\UserType;
use Models\WfpWcp;

allow(UserType::ALL);


if (isset($_POST['submit'])) {
    $user = unserialize($_SESSION['user']);
    $user->username = $_POST['username'];
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->gender = $_POST['gender'];
    $user->email = $_POST['email'];
    $user->address = $_POST['address'];
    $user->save();
    $_SESSION['user'] = serialize($user);
    $CONTEXT['user'] = $user;
}


$CONTEXT['tab'] = 'profile';

echo $twig->render('profile_details.html', $CONTEXT);
