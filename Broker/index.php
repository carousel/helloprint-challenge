<?php
//FRONT CONTROLLER
//
//allow headers on broker (browser policy)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require __DIR__ . '/vendor/autoload.php';

use App\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Broker;

$request = Request::createFromGlobals();
$validator = new Validator($request->getContent());

$response = new Response();
$response->headers->set('Content-Type', 'application/json');

//validate
if ($validator->errors) {
    $response->setContent(json_encode(array(
        'errors' => $validator->errors,
    )));
    $response->setStatusCode(422);
} else {

    $broker = new Broker;
    $message = $broker->message($request->getContent());
    $broker->publish($message);
    $broker->close();

    /*
    |------------------------------------------------------------------------------------------
    | NOTE consumer is publishing message back if user does not exits, or if credentials are wrong.
    | $consumer->publish(new PhpAmqpLib\Message\AMQPMessage('User does not exist'));
    | We should return error back to client:
    |-------------------------------------------------------------------------------------------
    */


    $response->setContent(json_encode(array(
        'success' => $validator->message,
    )));
    $response->headers->set('Content-Type', 'application/json');
    $response->setStatusCode(200);
}
//send HTTP response to client
$response->send();
