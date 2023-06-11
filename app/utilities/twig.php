<?php
require_once "/var/www/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('/var/www/templates');
$twig = new \Twig\Environment($loader);
