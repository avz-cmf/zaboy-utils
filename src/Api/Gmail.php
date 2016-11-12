<?php

namespace zaboy\utils\Api;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_MessagePart;

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
class Gmail
{

    //const APPLICATION_NAME = 'Gmail API PHP Quickstart';
    const CREDENTIALS_PATH = '~/.credentials/gmail-php-quickstart.json';

    //const CLIENT_SECRET_PATH = 'www/cfg/client_secret.json';

    /**
     *
     * @var Google_Client
     */
    public $googleClient;

    /**
     *
     * @var Google_Service_Gmail
     */
    public $googleService;

    /**
     *
     * @var array
     */
    public $messagesList = null;

    public function __construct()
    {
        $this->googleClient = $this->getClient();
        $this->googleService = new Google_Service_Gmail($this->googleClient);
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        $client = new Google_Client();
        //$client->setApplicationName(static::APPLICATION_NAME);
        //$client->setScopes(static::SCOPES);
        //$client->setAuthConfig(static::CLIENT_SECRET_PATH);
        //$client->setAccessType('offline');
        // Load previously authorized credentials from a file.
        $credentialsPath = self::expandHomeDirectory(static::CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            throw new \RuntimeException('There is not credentials. Run "php scripts/setGmailCredentials.php"');
        }
        $client->setAccessToken($accessToken);
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            // $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            // file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
            // save refresh token to some variable
            $refreshTokenSaved = $client->getRefreshToken();
            // update access token
            $client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            // pass access token to some variable
            $accessTokenUpdated = $client->getAccessToken();
            // append refresh token
            $accessTokenUpdated['refresh_token'] = $refreshTokenSaved;
            // save to file
            file_put_contents($credentialsPath, json_encode($accessTokenUpdated));
        }
        return $client;
    }

    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    public static function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

    public function getMessagesList()
    {
        return $this->messagesList ? $this->messagesList : $this->setMessagesList();
    }

    public function setMessagesList()
    {
        $nextPageToken = null;
        $list = [];
        do {
            $optParams = $nextPageToken ? ['pageToken' => $nextPageToken] : [];
            $optParams['maxResults'] = 1000; // Return Only 1000 Messages
            $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
            $messages = $this->googleService->users_messages->listUsersMessages('me', $optParams);
            $list = $messages->getMessages() ? array_merge($list, $messages->getMessages()) : $list;
            $nextPageToken = $messages->getNextPageToken();
        } while (!is_null($nextPageToken));
        $this->messagesList = $list;
        return $list;
    }

    public function getMessagesIds()
    {
        $list = $this->getMessagesList();
        $messageIds = [];
        foreach ($list as $message) {
            $messageIds[] = $message->getId();
        }
        return $messageIds;
    }

    public function getMessage($messageId)
    {
        $optParamsGet = [];
        $optParamsGet['format'] = 'full'; // Display message in payload
        $message = $this->googleService->users_messages->get('me', $messageId, $optParamsGet);
        return $message;
    }

    /**
     *
     * @param $message or $messageId
     * @return $messagePayload
     */
    public function getMessagePayload($message)
    {
        $message = is_scalar($message) ? $this->getMessage($message) : $message;
        $messagePayload = $message->getPayload();
        $messagePayload = is_null($messagePayload) ? $this->getMessage($message->getId())->getPayload() : $messagePayload;
        return $messagePayload;
    }

    public function getHeader($message, $name = null)
    {
        $headers = $this->getMessagePayload($message)->getHeaders();
        if (is_null($name)) {
            return $headers;
        }
        foreach ($headers as $header) {
            if ($header['name'] == $name) {
                return $header['value'];
            }
        }
        return null;
    }

    public function getSubject($message)
    {
        return $this->getHeader($message, 'Subject');
    }

    public function getFrom($message)
    {
        return htmlspecialchars($this->getHeader($message, 'From'));
    }

    public function getDate($message)
    {
        return $this->getHeader($message, 'Date');
    }

    /**
     *
     * @see http://stackoverflow.com/questions/24503483/reading-messages-from-gmail-in-php-using-gmail-api
     * @see http://stackoverflow.com/questions/32655874/cannot-get-the-body-of-email-with-gmail-php-api
     * @param type $part
     */
    public function getBodyHtml($message)
    {
        $payload = $this->getMessagePayload($message);
        $bodyData = $this->partsParse($payload);
        $bodyHtml = isset($bodyData['text/html']) ? $bodyData['text/html'] : null;
        return $bodyHtml;
    }

    public function getBodyTxt($message)
    {
        $payload = $this->getMessagePayload($message);
        $bodyData = $this->partsParse($payload);
        $bodyText = isset($bodyData['text/plain']) ? $bodyData['text/plain'] : null;
        return $bodyText;
    }

    protected function partsParse(Google_Service_Gmail_MessagePart $payload, $result = [])
    {
        $mimeType = $payload->getMimeType();
        if ($mimeType === 'text/plain' || $mimeType === 'text/html') {
            $decodedPart = base64_decode(str_replace(array('-', '_'), array('+', '/'), $payload->getBody()->getData()));
            $partId = $payload->getPartId();
            if (isset($result[$partId])) {
                throw new \RuntimeException('Part ' . $partId . ' exist.');
            }
            $result[$mimeType][$partId] = $decodedPart;
        }
        $parts = $payload->getParts() ? $payload->getParts() : [];
        foreach ($parts as $part) {
            $result = $this->partsParse($part, $result);
        }
        return $result;
    }

}
