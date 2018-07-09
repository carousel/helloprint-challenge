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
        $this->channel->queue_declare(getenv('QUEUE_NAME'), false, true, false, false);
    }
    public function listen($callback)
    {
        $this->channel->basic_consume(getenv('QUEUE_NAME'), '', false, true, false, false, [$this,'processUser']);
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
        $this->channel->basic_publish($message, '',getenv('QUEUE_NAME'));
    }
    public function processUser($msg)
    {
        $consumer = new Consumer;
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
    }
        
}











