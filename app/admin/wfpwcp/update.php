<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/files/uploader.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\WfpWcp;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN]);


if (isset($_GET['id'])) {
    $item = WfpWcp::get($_GET['id']);
    $CONTEXT['item'] = $item;
} else {
    exit(header('Location: /admin/wfpwcp/list.php'));
}

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $item = WfpWcp::get($_GET['id']);
    $item->permitee_name = $_POST['permitee_name'];
    $item->permitee_address = $_POST['permitee_address'];
    $item->farm_name = $_POST['farm_name'];
    $item->farm_address = $_POST['farm_address'];
    $item->wfp_no = $_POST['wfp_no'];
    $item->wcp_no = $_POST['wcp_no'];
    $item->issuance_date = $_POST['issuance_date'];
    $item->expiry_date = $_POST['expiry_date'];

    $wfp_photo_link = save($_FILES['wfp_photo']);
    if ($wfp_photo_link != null) $item->wfp_photo_link = $wfp_photo_link;

    $wcp_photo_link = save($_FILES['wcp_photo']);
    if ($wcp_photo_link != null) $item->wcp_photo_link = $wcp_photo_link;

    $permitee_photo_link = save($_FILES['permitee_photo']);
    if ($permitee_photo_link != null) $item->permitee_photo_link = $permitee_photo_link;

    $farm_photo_link = save($_FILES['farm_photo']);
    if ($farm_photo_link != null) $item->farm_photo_link = $farm_photo_link;

    $item->save();
    exit(header('Location: /admin/wfpwcp/list.php'));
}

$CONTEXT['tab'] = 'wfpwcp';

echo $twig->render('wfpwcp_update.html', $CONTEXT);
