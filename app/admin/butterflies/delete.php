<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/butterflies.php';

use Models\UserType;
use Models\Butterfly;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    Butterfly::get($_GET['id'])->delete();
}

exit(header('Location: /admin/butterflies/list.php'));
