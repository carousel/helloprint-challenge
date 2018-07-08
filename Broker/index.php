<?php

//front controller

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
require __DIR__ . '/vendor/autoload.php';

use App\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \App\Broker;

$request = Request::createFromGlobals();
$validator = new Validator($request->getContent());

$response = new Response();
$response->headers->set('Content-Type', 'application/json');
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

    $response->setContent(json_encode(array(
        'success' => $validator->message,
    )));
    $response->headers->set('Content-Type', 'application/json');
    $response->setStatusCode(200);
}
$response->send();
