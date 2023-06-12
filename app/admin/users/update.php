<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\User;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN]);


if (isset($_GET['id'])) {
    $user = User::get($_GET['id']);
    $CONTEXT['editing_user'] = $user;
} else {
    exit(header('Location: /admin/users/list.php'));
}

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $user = User::get($_GET['id']);
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->gender = $_POST['gender'];
    $user->type = $_POST['type'];
    $user->active = $_POST['active'];
    $user->save();
    exit(header('Location: /admin/users/list.php'));
}

$CONTEXT['tab'] = 'users';

echo $twig->render('user_update.html', $CONTEXT);
