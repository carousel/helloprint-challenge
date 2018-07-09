<?php
//FRONT CONTROLLER

require __DIR__ . '/vendor/autoload.php';

use App\Persistence\DB;
use App\Persistence\UserRepository;
use App\Services\Auth;
use App\Services\Consumer;

$consumer = new Consumer;

$callback = function ($msg) {
    $user = new UserRepository(new DB);
    $auth = new Auth($user);
    $data = json_decode($msg->body, true);
    $type = $data['type'];
    if ($type == 'login') {
        $logged = $auth->login($data);
        if($logged == false){
            $consumer->publish(new PhpAmqpLib\Message\AMQPMessage('User does not exist'));
        }
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
