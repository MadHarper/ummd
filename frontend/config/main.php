<?php

use common\models\UserToris;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'homeUrl' => ['/agreement/default/index'],
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'toris'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru',
    'modules' => [
        'toris' => [
            'class' => 'frontend\modules\toris\Module',
        ],
        'catalog' => [
            'class' => 'frontend\modules\catalog\Module',
        ],
        'agreement' => [
            'class' => 'frontend\modules\agreement\Module',
        ],
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            // other module settings
        ],
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
            // other module settings
        ],
        'mission' => [
            'class' => 'frontend\modules\mission\Module'
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => UserToris::class,
            //'enableAutoLogin' => false,
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['/site/login']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/agreement/default/index'
            ],
        ],
    ],
    'params' => $params,
];
