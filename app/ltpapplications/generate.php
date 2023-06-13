<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\LtpApplication;
use Models\WfpWcp;
use DTOs\Search;

allow([UserType::CLIENT]);

$ltp = LtpApplication::filter(Search::create(['id' => $_GET['id'], 'client_id' => $CONTEXT['user']->id]))->items[0];

$CONTEXT['ltp'] = $ltp;

echo $twig->render('ltp.html', $CONTEXT);
