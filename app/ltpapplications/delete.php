<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';

use Models\UserType;
use Models\LtpApplication;

allow([UserType::ADMIN, UserType::CLIENT]);

if (isset($_GET['id'])) {
    LtpApplication::get($_GET['id'])->delete();
}

exit(header('Location: /ltpapplications/list.php'));
