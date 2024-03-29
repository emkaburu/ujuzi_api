<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_QQxyr6SfMwT9xidXpxZJSRmZs1DwS6P',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'dashboard' => 'site/index',
                'GET api/categories' => 'crop-category/list-all',
                'GET api/categories/<uid>' => 'crop-category/view',
                'POST api/categories' => 'crop-category/create',
                'PUT api/categories/<uid>' => 'crop-category/update',
                'DELETE api/categories/<uid>' => 'crop-category/delete',

                // Crop Routes
                'GET api/crops' => 'cropec/list-all',
                'GET api/crops/<uid>' => 'cropec/view',
                'POST api/crops' => 'cropec/create',
                'PUT api/crops' => 'cropec/update',
                'PUT api/crops/<uid>' => 'cropec/update',
                'DELETE api/crops/<uid>' => 'cropec/delete',

                'OPTIONS /api/crops' => 'cropec/preflight',
                'OPTIONS /api/crops/<uid>' => 'cropec/preflight',
                'OPTIONS /api/categories' => 'cropec/preflight',
                'OPTIONS /api/categories/<uid>' => 'cropec/preflight'
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
