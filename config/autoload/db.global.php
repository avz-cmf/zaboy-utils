<?php

return [

    'db' => [
        'adapters' => [
            'db' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'zaboy',
                'username' => 'zaboy_test',
                'password' => '123321qweewq'
            ],
            'testDb' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'zaboy_test',
                'username' => 'zaboy_test',
                'password' => '123321qweewq'
            ],
        ]
    ],
    'services' => [
        'abstract_factories' => [
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ],
        'aliases' => [
            //this 'callback' is service name in url
            'emailDbAdapter' => getenv('APP_ENV') === 'production' ? 'db' : 'testDb',
            'rockyMountainDbAdapter' => getenv('APP_ENV') === 'production' ? 'db' : 'testDb',
        ],
    ],
];
