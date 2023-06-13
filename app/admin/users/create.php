<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/messaging/mailer.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\User;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN]);


if (isset($_POST['submit'])) {
    $password = strtolower(str_replace('-', '', Uuid::uuid4()->toString()));
    $user = User::create($_POST);
    $user->set_password($password);
    $user->save();

    $message = "
    User Type: $user->type\n
    Username: $user->username\n
    Temporary Password: $password
    ";
    send_mail($user->email, $message);

    exit(header('Location: /admin/users/list.php'));
}


$CONTEXT['tab'] = 'users';

echo $twig->render('user_create.html', $CONTEXT);
