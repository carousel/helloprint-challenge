<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Services\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;


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
        $this->channel->basic_consume(getenv('QUEUE_NAME'), '', false, true, false, false, $callback);
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
}









//$channel->exchange_declare('antitask', 'direct', false, false, true, false, false, ['foo' => ['b', 'bar']]);


//$msg = new AMQPMessage('Date and time: ' . Carbon::today());
