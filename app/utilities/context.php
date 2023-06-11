<?php
$ROOT_DIR = '/var/www/utilities';
require_once '/var/www/utilities/auth/session.php';

$CONTEXT = array();

if (isset($_SESSION['user'])) {
    $CONTEXT['user'] = unserialize($_SESSION['user']);
}
