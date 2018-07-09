<?php
//FRONT CONTROLLER

require __DIR__ . '/vendor/autoload.php';

use App\Services\Consumer;

$consumer = new Consumer;
$consumer->listen();
