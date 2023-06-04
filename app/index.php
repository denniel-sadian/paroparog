<?php
require_once 'twig.php';
require_once 'utilities/db/models/butterflies.php';

$data = [
	"title" => "My Page Title",
	"message" => Models\Butterfly::search([])
];

echo $twig->render('index.html', $data);
