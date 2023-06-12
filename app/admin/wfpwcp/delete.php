<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';

use Models\UserType;
use Models\WfpWcp;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    WfpWcp::get($_GET['id'])->delete();
}

exit(header('Location: /admin/wfpwcp/list.php'));
