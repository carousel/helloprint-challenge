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
        $this->connection = new AMQPStreamConnection('146.185.140.33', 5672, 'test', 'test');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(getenv('QUEUE_NAME'), false, true, false, false);
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
        $this->channel->basic_publish($message, '', getenv('QUEUE_NAME') );
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

}
