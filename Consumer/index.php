<?php

require __DIR__ . '/vendor/autoload.php';

use App\Persistence\DB;
use App\Persistence\UserRepository;
use App\Services\Auth;
use App\Services\Consumer;

$consumer = new Consumer;
$callback = function ($msg) {
    $data = json_decode($msg->body, true);
    $type = $data['type'];
    $db = new UserRepository(new DB);
    $auth = new Auth($db);
    if ($type == 'login') {
        $auth->login($data);
    }
    if ($type == 'register') {
        $db->addNewUser($data);
    }
    if ($type == 'recovery') {
        $db->changePassword($data);
    }
};
$consumer->listen($callback);
