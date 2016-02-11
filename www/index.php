<?php
// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require '/vendor/autoload.php';

use Zend\Stratigility\MiddlewarePipe;
use Zend\Diactoros\Server;
use zaboy\rest\Pipes\Factory\RestPipeFactory;
use zaboy\utils\Middleware;

$container = include 'config/container.php';
$tableName = 'index_php_table';
//include 'createTable.php';


$app = new MiddlewarePipe();
$sessionMiddleware = new Middleware\SessionInjector();
$restPipeFactory = new RestPipeFactory();//[450 => $sessionMiddleware]);
$rest = $restPipeFactory($container, '');
$app->pipe('/rest', $rest);

$app->pipe('/main', new Middleware\HeadWithDojo());

$server = Server::createServer($app,
  $_SERVER,
  $_GET,
  $_POST,
  $_COOKIE,
  $_FILES
);
$server->listen();
/*
$deleteStatementStr = "DROP TABLE IF EXISTS " .  $quoteTableName;
$deleteStatement = $adapter->query($deleteStatementStr);
$deleteStatement->execute();
 
 */