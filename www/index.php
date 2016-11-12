<?php

// Define application environment
if (getenv('APP_ENV') === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$container = include 'config/container.php';

use zaboy\utils\utils\HtmlParser\Simple as HtmlParserSimple;
use zaboy\utils\Api\Gmail as ApiGmail;

$apiGmail = new ApiGmail();
$list = $apiGmail->getMessagesList();
//var_dump($apiGmail->getBodyTxt($list[8]));
foreach ($list as $value) {
    $txt = $apiGmail->getBodyHtml($value);
    echo $txt[1];
}





// Create a DOM object from a string
//$htmlParserSimple = new HtmlParserSimple;
//$html = HtmlParserSimple::strGetHtml('<html><body>Hello!</body></html>');
//$html->dump();
//var_dump($htmlParserSimple);
