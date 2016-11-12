<?php

namespace zaboy\utils\DataStore;

use zaboy\rest\DataStore\DbTable;
use Zend\Db\TableGateway\TableGateway;
use zaboy\utils\Api\Gmail as ApiGmail;

/**
 *
 * time GMT
 * @see http://stackoverflow.com/questions/33912834/gmail-api-not-returning-correct-emails-compared-to-gmail-web-ui-for-date-queries/33919375#33919375
 * @see http://stackoverflow.com/questions/33552890/why-does-search-in-gmail-api-return-different-result-than-search-in-gmail-websit
 *
 * @see http://stackoverflow.com/search?q=PHP%2C+Gmail+API+read
 * @see https://github.com/adevait/GmailPHP/blob/master/examples/messages.php
 * @see https://developers.google.com/gmail/api/quickstart/php#step_1_install_the_google_client_library
 */
class Email extends DbTable
{

    const TABLE_NAME = 'emails';
    //
    const MESSAGE_ID = 'id';
    const SUBJECT = 'subject';
    const SENDING_TIME = 'sending_time';
    const BODY_HTML = 'body_html';
    const BODY_TXT = 'body_txt';
    const HEADERS = 'headers';

    /**
     *
     * @var ApiGmail
     */
    protected $apiEmail;

    public function __construct(ApiGmail $apiEmail)
    {
        $dbTable = new TableGateway(static::TABLE_NAME);
        parent::__construct($dbTable);
        $this->apiEmail = $apiEmail;
    }

    public function addMessage($message)
    {
        $messageId = is_object($message) ? $message->getId() : $message;

        $item[self::MESSAGE_ID] = $messageId;
        $item[self:: SUBJECT] = $this->apiEmail->getSubject($message);
        $item[self:: SENDING_TIME];
        $item[self:: BODY_HTML] = $this->apiEmail->getBodyHtml($message);
        $item[self:: BODY_TXT] = $this->apiEmail->getBodyTxt($message);
        $item[self:: HEADERS] = $this->apiEmail->getHeader($message);
    }

}