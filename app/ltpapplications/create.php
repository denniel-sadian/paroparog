<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/files/uploader.php';

use Models\UserType;
use Models\User;
use Models\LtpApplication;
use Models\Status;

allow([UserType::ADMIN, UserType::CLIENT]);

$req = new LtpApplication();
$req->client_id = unserialize($_SESSION['user'])->id;
$req->created_at = date("Y-m-d", strtotime("today"));
$req->status = Status::DRAFT;
$req->save();
$req->no = 'PMDQ-LTP-'.date("Y-m").date("d", strtotime("today")).'-'.$req->id;
$req->save();

exit(header('Location: /ltpapplications/update.php?id='.$req->id));
