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

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}

$search = null;
if (isset($_GET['search']) && $_GET['search'] != '') {
    $CONTEXT['search'] = $_GET['search'];
    $search = Search::create([
        'specie_type' => $_GET['search'],
        'class_name' => $_GET['search'],
        'family_name' => $_GET['search']
    ], 'OR', false);
}

$page = Butterfly::filter($search, Sort::create('specie_type', 'ASC'), PageRequest::create($_GET['page']-1, $_ENV['PAGE_SIZE']));

$CONTEXT['tab'] = 'butterflies';
$CONTEXT['page'] = $page;

echo $twig->render('butterfly_list.html', $CONTEXT);
