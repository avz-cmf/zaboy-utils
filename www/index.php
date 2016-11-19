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
use zaboy\utils\DataStore\Email;
use Xiag\Rql\Parser\Query;
use zaboy\rest\RqlParser\RqlParser;
use zaboy\utils\Services\RockyMountain\EmailParser;

$dataStore = new Email;
$apiGmail = $dataStore->getApiGmail();
$emailParser = new EmailParser($dataStore);
$mwssagesList = $apiGmail->queryMessagesList('-"Replacement for"  placing Rocky Mountain   '); //'placing Rocky Mountain' 'From:giftcertificate@rockymountainatv.com' 9600275 10072491

echo' <!DOCTYPE HTML><html> <head>  <meta charset="utf-8">  <title>Таблица </title> </head> <body>';
echo ('<table>');
foreach ($mwssagesList as $messageFromList) {
    $message = $dataStore->read($messageFromList->getId());
    if ($emailParser->getType($message) === EmailParser::TYPE_RM_ORDER_PLACING) {
        echo$emailParser->fillOrderPlacing($message);
    }
}
echo ('</table>');
echo '</body> </html>';
exit;




//$list = $apiGmail->queryMessagesList();
//foreach ($list as $value) {
//
//    $dataStore->addMessageId($value);
//}
//exit;
//$list = $apiGmail->queryMessagesList(); //$list = $apiGmail->getMessagesList();
//$list = $apiGmail->queryMessagesList();
//$rqlQueryString = 'select(id)';
//$query = RqlParser::rqlDecode($rqlQueryString);
//$listStore = $dataStore->query($query);
//array_diff()
//D:\OpenServer\modules\wget\bin\wget.exe -q --no-cache http://zaboy-utils/index.php
//var_dump($apiGmail->getBodyTxt($list[8]));

$i = 0;
$rqlQueryString = 'eq(sending_time,null)&limit(50)';
$query = RqlParser::rqlDecode($rqlQueryString);
$nextItems = $dataStore->query($query);
foreach ($nextItems as $value) {
    //$i = $i + 1;
    $id = $value["id"];
    $dataStore->addMessageData($id);
    echo $id . ' <br> ';
//    if (round($i / 100) === $i / 100) {
//        echo $i . ' <br> ';
//    }
}
exit;

//$dataStore->addMessage($list[0]);
// Create a DOM object from a string
//$htmlParserSimple = new HtmlParserSimple;
//$html = HtmlParserSimple::strGetHtml('<html><body>Hello!</body></html>');
//$html->dump();
//var_dump($htmlParserSimple);



