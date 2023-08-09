<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/db/models/allowed_animals.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\WfpWcp;
use Models\Butterfly;
use Models\AllowedAnimal;
use DTOs\Search;

allow([UserType::ADMIN]);

if (isset($_POST['submit'])) {
    $present_allowed = AllowedAnimal::filter_all(Search::create(['wcp_id' => $_POST['wcp_id'], 'animal_id' => $_POST['animal_id']]));
    if (count($present_allowed) == 0) {
        $entry = new AllowedAnimal();
        $entry->wcp_id = $_POST['wcp_id'];
        $entry->animal_id = $_POST['animal_id'];
        $entry->quantity = $_POST['quantity'];
        $entry->save();
    }
}

exit(header('Location: /admin/wfpwcp/update.php?id='.$_POST['wcp_id'].'#allowed-list'));
