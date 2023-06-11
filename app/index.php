<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';

//if(isset($_POST["submit"])) {
//    $file = $_FILES["fileToUpload"];
//    $data['src'] = save($file);
//}

//send_mail('sadiandenniel@gmail.com', 'Yass bitch!');

//$user = Models\User::create([
//    'username' => 'denniel',
//    'email' => 'denniel@purplme.com',
//    'first_name' => 'Denniel',
//    'last_name' => 'Sadian',
//    'gender' => Models\Gender::MALE,
//    'type' => Models\UserType::ADMIN,
//    'password' => '12341234',
//    'active' => true
//]);
//$user->save();

echo $twig->render('index.html', $CONTEXT);
