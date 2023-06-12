<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\WfpWcp;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN]);

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}

$search = null;
if (isset($_GET['search']) && $_GET['search'] != '') {
    $txt = $_GET['search'];
    $CONTEXT['search'] = $txt;
    $search = Search::create([
        'username' => $txt,
        'email' => $txt,
        'password' => $txt,
        'first_name' => $txt,
        'last_name' => $txt,
        'type' => $txt
    ], 'OR', false);
}

$page = WfpWcp::filter($search, Sort::create('issuance_date', 'DESC'), PageRequest::create($_GET['page']-1, $_ENV['PAGE_SIZE']));

$CONTEXT['tab'] = 'wfpwcp';
$CONTEXT['page'] = $page;

echo $twig->render('wfpwcp_list.html', $CONTEXT);
