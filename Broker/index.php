<?php
//FRONT CONTROLLER
//
//allow headers on broker (browser policy)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require __DIR__ . '/vendor/autoload.php';

use Monolog\ErrorHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;
use App\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Broker;

$logger = new Logger('broker-logger');
ErrorHandler::register($logger);
$handler = new ErrorHandler($logger);
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();
$formatter = new JsonFormatter();
$logger->pushHandler(new StreamHandler('./logger.log', Logger::WARNING));


$request = Request::createFromGlobals();
$validator = new Validator($request->getContent());

$response = new Response();
$response->headers->set('Content-Type', 'application/json');
$broker = new Broker;
//$broker->listen();

//validate
if ($validator->errors) {
    $response->setContent(json_encode(array(
        'errors' => $validator->errors,
    )));
    $response->setStatusCode(422);
} else {
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
