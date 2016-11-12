<?php

return [
    'services' => [
        'aliases' => [
            //this 'callback' is service name in url
            'emailDbAdapter' => getenv('APP_ENV') === 'production' ? 'db' : 'testDb',
        ],
    ],
];
