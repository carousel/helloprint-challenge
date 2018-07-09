<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
        $this->connection = new AMQPStreamConnection('146.185.140.33', 5672, 'test', 'test');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('helloprint', false, true, false, false);
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
        $this->channel->basic_publish($message, '', 'broker message');
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
