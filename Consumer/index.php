<?php
//FRONT CONTROLLER

require __DIR__ . '/vendor/autoload.php';

use App\Persistence\DB;
use App\Persistence\UserRepository;
use App\Services\Auth;
use App\Services\Consumer;

$consumer = new Consumer;
$user = new UserRepository(new DB);
$auth = new Auth($user);

$callback = function ($msg) {
    $data = json_decode($msg->body, true);
    $type = $data['type'];
    if ($type == 'login') {
        $auth->login($data);
    }
    if ($type == 'register') {
        $user->addNewUser($data);
    }
    if ($type == 'recovery') {
        $user->changePassword($data);
    }
};

//listen for incoming messages
$consumer->listen($callback);
