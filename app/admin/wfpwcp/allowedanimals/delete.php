<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/allowed_animals.php';

use Models\UserType;
use Models\Butterfly;
use Models\AllowedAnimal;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    $entry = AllowedAnimal::get($_GET['id']);
    $entry->delete();
    exit(header('Location: /admin/wfpwcp/update.php?id='.$entry->wcp_id.'#allowed-list'));
}

