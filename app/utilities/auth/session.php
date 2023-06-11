<?php
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/context.php';

use Models\User;
use DTOs\Search;

session_start();


function login($username, $password) {
    $user = Models\User::filter(Search::create([
        'username' => $username,
        'password' => md5($password),
        'active' => true
    ]))->items[0];

    if ($user != null) {
        $_SESSION['user'] = serialize($user);
    }

    return $user;
}
