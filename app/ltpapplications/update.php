<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/butterflies.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/files/uploader.php';
require_once '/var/www/utilities/files/uploader.php';
require_once '/var/www/utilities/messaging/mailer.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\User;
use Models\LtpApplication;
use Models\Status;
use Models\Butterfly;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;

allow(UserType::ALL);

if (isset($_GET['id'])) {
    $item = null;
    if ($CONTEXT['user']->type == UserType::CLIENT) {
        $items = LtpApplication::filter(Search::create(['id' => $_GET['id'], 'client_id' => $CONTEXT['user']->id]))->items;
        if (count($items) == 0) {
            exit(header('Location: /forbidden.php'));
        } else {
            $item = $items[0];
        }
    } else {
        $item = LtpApplication::get($_GET['id']);
    }

    $CONTEXT['item'] = $item;
    $CONTEXT['animals'] = Butterfly::filter_all(null, Sort::create('common_name', 'ASC'));
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

    if (isset($_FILES['inspection_report'])) {
        $inspection_report = save($_FILES['inspection_report']);
        if ($inspection_report != null) $item->inspection_report_link = $inspection_report;
    }

    if (isset($_FILES['or'])) {
        $or = save($_FILES['or']);
        if ($or != null) $item->or_link = $or;
    }

    if ($CONTEXT['user']->type == UserType::PERMIT_SIGNATORY) {
        if (isset($_FILES['permit_signature'])) {
            $permit_signature = save($_FILES['permit_signature']);
            if ($permit_signature != null) {
                $item->permit_signature_link = $permit_signature;
                $item->permit_signatory_id = $CONTEXT['user']->id;
            }
        }
    }

    if ($CONTEXT['user']->type == UserType::RELEASING_PERSONNEL) {
        if (isset($_POST['validity_date'])) {
            $item->releasing_personnel_id = $CONTEXT['user']->id;
            $item->validity_date = $_POST['validity_date'];
            $item->release_date = date("Y-m-d", strtotime("today"));
            $item->status = Status::RELEASED;
            notify_client_about_released($item);
        }
    }

    $item->updated_at = date("Y-m-d", strtotime("today"));
    $item->save();
    exit(header('Location: /ltpapplications/update.php?id='.$item->id));
}

$CONTEXT['tab'] = 'ltpapplications';

echo $twig->render('ltpapp_update.html', $CONTEXT);
