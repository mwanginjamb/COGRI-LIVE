<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'COGRI - HRMIS',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:server='.env('DB_SERVER').';database='.env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PWD'),
            'charset' => 'utf8',
        ],
         'nav' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:server='.env('DB_SERVER').';database='.env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PWD'),
            'charset' => 'utf8',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => ['/plugins/jquery/jquery.js'],
                ]
            ]
        ],
         'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
             'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => env('SMTPSERVER'),
                'username' => env('SMTPUSER'),
                'password' => env('SMTPPWD'),
                'port' => env('PORT'),
                'encryption' => env('ENCRYPTION'),

            ],
        ],
        'navision' => [
            'class' => 'common\Library\Navision',
        ],
        'navhelper' => [
            'class' => 'common\Library\Navhelper',
        ],
        'recruitment' => [
            'class' => 'common\Library\Recruitment'
        ],
        'dashboard' => [
            'class' => 'common\Library\Dashboard'
        ],
    ],
];
