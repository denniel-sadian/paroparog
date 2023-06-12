<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/files/uploader.php';

use Models\UserType;
use Models\WfpWcp;

allow([UserType::ADMIN]);


if (isset($_POST['submit'])) {
    $_POST['wfp_photo_link'] = save($_FILES['wfp_photo']);
    $_POST['wcp_photo_link'] = save($_FILES['wcp_photo']);
    $_POST['permitee_photo_link'] = save($_FILES['permitee_photo']);
    $_POST['farm_photo_link'] = save($_FILES['farm_photo']);

    $permits = WfpWcp::create($_POST);
    $permits->save();

    exit(header('Location: /admin/wfpwcp/list.php'));
}


$CONTEXT['tab'] = 'wfpwcp';

echo $twig->render('wfpwcp_create.html', $CONTEXT);
