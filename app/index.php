<?php
require_once 'twig.php';

$data = [
	"title" => "My Page Title",
	"message" => $_ENV['DB_USER']
];

echo $twig->render('index.html', $data);
