<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/butterflies.php';

use Models\UserType;
use Models\Butterfly;

allow([UserType::ADMIN]);

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $butterfly = Butterfly::get($_GET['id']);
    $butterfly->specie_type = $_POST['specie_type'];
    $butterfly->class_name = $_POST['class_name'];
    $butterfly->family_name = $_POST['family_name'];
    $butterfly->save();
}

exit(header('Location: /admin/butterflies/list.php'));
