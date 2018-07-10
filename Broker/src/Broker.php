<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Dotenv\Dotenv;

class Broker
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;
    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();
        $this->connection = new AMQPStreamConnection(getenv('CONSUMER_URL'), 5672, 'test', 'test');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(getenv('INPUT_QUEUE'), false, true, false, false);
    }

    /**
     * @param $text
     * @return AMQPMessage
     */
    public function message($text)
    {
        return new AMQPMessage($text);
    }

    /**
     * @param $message
     */
    public function publish($message)
    {
        $this->channel->basic_publish($message, '', getenv('INPUT_QUEUE'));
    }

    public function listen()
    {
        $this->channel->basic_consume(getenv('OUTPUT_QUEUE'), '', false, true, false, false, [$this,'status']);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
    public function status($message)
    {
        return true;
        //var_dump($message->body);
    }
        
}
