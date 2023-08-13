<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Status;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow(UserType::ALL);

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}

$search = Search::create();
if (isset($_GET['search']) && $_GET['search'] != '') {
    $txt = $_GET['search'];
    $CONTEXT['search'] = $txt;
    $search = Search::create([
        'no' => $txt,
        'transport_address' => $txt,
        'status' => $txt,
    ], 'OR', false);
}

if (isset($_GET['status'])) {
    $CONTEXT['status'] = $_GET['status'];
    if ($_GET['status'] != 'ALL') {
        $search->fields['status'] = $_GET['status'];
    }
} else {
    $CONTEXT['status'] = 'ALL';
}

if ($CONTEXT['user']->type == UserType::CLIENT) {
    $search->extra = "client_id=".$CONTEXT['user']->id;
}

$page = LtpApplication::filter($search, Sort::create('id', 'DESC'), PageRequest::create($_GET['page']-1, $_ENV['PAGE_SIZE']));

$CONTEXT['tab'] = 'ltpapplications';
$CONTEXT['page'] = $page;
$CONTEXT['statuses'] = Status::ALL;

echo $twig->render('ltpapp_list.html', $CONTEXT);
