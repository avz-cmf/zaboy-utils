<?php

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

//require_once __DIR__ . '/vendor/autoload.php';

use zaboy\utils\Api\Gmail as ApiGmail;

define('APPLICATION_NAME', 'Gmail API PHP Quickstart');
define('CREDENTIALS_PATH', ApiGmail::CREDENTIALS_PATH);
define('CLIENT_SECRET_PATH', 'www/cfg/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/gmail-php-quickstart.json
define('SCOPES', implode(' ', array(
    Google_Service_Gmail::GMAIL_READONLY)
));

$client = new Google_Client();
$client->setApplicationName(APPLICATION_NAME);
$client->setScopes(SCOPES);
$client->setAuthConfig(CLIENT_SECRET_PATH);
$client->setAccessType('offline');

// Load previously authorized credentials from a file.
$credentialsPath = ApiGmail::expandHomeDirectory(CREDENTIALS_PATH);
if (file_exists($credentialsPath)) {
    printf("Credentials exist in %s\n", $credentialsPath);
    echo(PHP_EOL . 'Delete it for remake and restsrt this script');
} else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if (!file_exists(dirname($credentialsPath))) {
        mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
}

exit;

