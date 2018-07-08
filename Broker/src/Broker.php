<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Carbon\Carbon;


//tommorows - env data

class Broker
{
    private $connection;
    private $channel;
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('146.185.140.33', 5672, 'test', 'test');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('helloprint', false, false, false, false);
    }
    public function message($text)
    {
        return new AMQPMessage($text);
    }
    public function publish($message)
    {
        $this->channel->basic_publish($message, '', 'helloprint');
    }
    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
    public function i($input)
    {
        ;
    }
        
}









//$channel->exchange_declare('antitask', 'direct', false, false, true, false, false, ['foo' => ['b', 'bar']]);


//$msg = new AMQPMessage('Date and time: ' . Carbon::today());
