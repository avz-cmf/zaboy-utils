<?php
// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require '/vendor/autoload.php';

use Zend\Stratigility\MiddlewarePipe;
use Zend\Diactoros\Server;
use zaboy\rest\Middleware;
use zaboy\rest\Pipes\Factory\RestPipeFactory;

$container = include 'config/container.php';

$app = new MiddlewarePipe();
$rest = (new RestPipeFactory())->__invoke($container, '');
$app->pipe('/rest', $rest);

$server = Server::createServer($app,
  $_SERVER,
  $_GET,
  $_POST,
  $_COOKIE,
  $_FILES
);
$server->listen();
