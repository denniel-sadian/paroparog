<?php
require_once __DIR__.'/../db/models/users.php';

session_start();


function login($username, $password) {
    $user = Models\User::search(
        [
            'username' => $username,
            'password' => md5($password),
            'active' => true
        ],
        true)[0];

    if ($user != null) {
        $_SESSION['username'] = serialize($user);
    }

    return $user;
}
