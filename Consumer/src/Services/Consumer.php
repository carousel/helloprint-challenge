<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Services\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
use Dotenv\Dotenv;
use App\Persistence\UserRepository;
use App\Services\Auth;
use App\Services\Consumer;
use App\Persistence\DB;


//tommorows - env data

class Consumer
{
    private $connection;
    private $channel;
    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../');
        $dotenv->load();
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(getenv('OUTPUT_QUEUE'), false, true, false, false);
    }
    public function listen()
    {
        $this->channel->basic_consume(getenv('INPUT_QUEUE'), '', false, true, false, false, [$this,'processUser']);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
    /**
     * @param $message
     */
    public function publish($message)
    {
        $this->channel->basic_publish($message, '',getenv('OUTPUT_QUEUE'));
    }
    public function processUser($msg)
    {
        $user = new UserRepository(new DB);
        $auth = new Auth($user);
        $data = json_decode($msg->body, true);
        $type = $data['type'];
        if ($type == 'login') {
            $logged = $auth->login($data);
            if($logged == false){
                $this->publish(new AMQPMessage('User does not exist'));
                echo $data['username'] . ' does not exist' . "\n";
            }else{
                echo $data['username'] . ' logged' . "\n";
            }
        }
        if ($type == 'register') {
            $user->addNewUser($data);
            echo $data['username'] . ' registered' . "\n";
        }
        if ($type == 'recovery') {
            $exists = $auth->exists($data['username']);
            if($exists == false){
                $this->publish(new AMQPMessage('User does not exist'));
                echo "\n";
                echo $data['username'] . ' does not exist' . "\n";
            }else{
                $user->changePassword($data);
                echo "\n";
                echo 'Password changed for ' . $data['username'] . "\n";
            }
        }
    }
        
}











