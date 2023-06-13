<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplication.php';
require_once '/var/www/utilities/db/models/butterflies.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/files/uploader.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\User;
use Models\LtpApplication;
use Models\Butterfly;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow([UserType::ADMIN, UserType::CLIENT]);

if (isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    $CONTEXT['item'] = $item;
    $CONTEXT['animals'] = Butterfly::filter_all(null, Sort::create('specie_type', 'ASC'));
} else {
    exit(header('Location: /admin/ltpapplications/list.php'));
}

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    $item->transport_address = $_POST['transport_address'];
    $item->transport_date = $_POST['transport_date'];

    $veterinary_quarantine_cert = save($_FILES['veterinary_quarantine_cert']);
    if ($veterinary_quarantine_cert != null) $item->veterinary_quarantine_cert_link = $veterinary_quarantine_cert;

    $supporting_docs = save($_FILES['supporting_docs']);
    if ($supporting_docs != null) $item->supporting_docs_link = $supporting_docs;

    $item->save();
    exit(header('Location: /ltpapplications/update.php?id='.$item->id));
}

$CONTEXT['tab'] = 'ltpapplications';

echo $twig->render('ltpapp_update.html', $CONTEXT);
