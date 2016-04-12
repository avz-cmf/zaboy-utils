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
$tableName = 'test_res_http';//'index_php_table';
//include 'createDojoData.php';

$app = new MiddlewarePipe();
$restPipeFactory = new RestPipeFactory();
$rest = $restPipeFactory($container, '');
$app->pipe('/rest', $rest);

$app->pipe('/main', new Middleware\MainHtml());

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