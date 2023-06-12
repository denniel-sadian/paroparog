<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';

use Models\UserType;
use Models\User;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    User::get($_GET['id'])->delete();
}

exit(header('Location: /admin/users/list.php'));
