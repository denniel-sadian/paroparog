<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/auth/session.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/wfp_wcp.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/messaging/mailer.php';

use Ramsey\Uuid\Uuid;
use Models\UserType;
use Models\User;
use Models\WfpWcp;
use DTOs\Search;
use DTOs\Sort;
use DTOs\PageRequest;


$context = array();
$context['tab'] = 'register';


function create_client($wfpwcp) {
    $_POST['type'] = UserType::CLIENT;
    $user = User::create($_POST);
    $user->wfpwcp_id = $wfpwcp->id;

    $password = strtolower(str_replace('-', '', Uuid::uuid4()->toString()));
    $user->set_password($password);

    $user->save();

    $message = "
    Good day, $user->first_name $user->last_name. Please use these credentials for login.\n
    Username: $user->username\n
    Temporary Password: $password
    ";
    send_mail($user->email, $message);

    exit(header('Location: /login.php'));
}


function validate_form() {
    global $context;
    // Check that the wfpwcp_no is present and not expired MIMAROPA-2020-03
    $wfpwcp_no = $_POST['wfpwcp_no'];
    $search = Search::create(['wfp_no' => $wfpwcp_no, 'wcp_no' => $wfpwcp_no], 'OR');
    $items = WfpWcp::filter($search)->items;
    $wfpwcp = count($items) == 1 ? $items[0] : null;
    if ($wfpwcp == null || ($wfpwcp != null && $wfpwcp->expired)) {
        $context['error'] = "No valid Wildlife Farm/Collector's Permit found.";
        return;
    }

    // Check that the entered permit number is not yet taken
    $search = Search::create(['wfpwcp_id' => $wfpwcp->id]);
    $items = User::filter($search)->items;
    if (count($items) != 0) {
        $context['error'] = "The permit number $wfpwcp_no is already used by another user.";
        return;
    }

    // Check if email is already taken
    $email = $_POST['email'];
    $search = Search::create(['email' => $email]);
    $items = User::filter($search)->items;
    if (count($items) != 0) {
        $context['error'] = "Email is registered already.";
        return;
    }

    create_client($wfpwcp);
}

if (isset($_POST['submit'])) {
    validate_form();
}

echo $twig->render('register.html', $context);
