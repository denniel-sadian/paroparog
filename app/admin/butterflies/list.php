<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/butterflies.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\Butterfly;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN]);

if ($_GET['page'] == null) $_GET['page'] = 1;

$page = Butterfly::filter(null, null, PageRequest::create($_GET['page']-1, 5));

$CONTEXT['tab'] = 'butterflies';
$CONTEXT['page'] = $page;

echo $twig->render('butterfly_list.html', $CONTEXT);
