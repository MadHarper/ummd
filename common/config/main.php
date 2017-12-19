<?php
return [
    'bootstrap' => [
        'queue', // Компонент регистрирует свои консольные команды
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'seaFileService' => [
            'class' => 'common\services\SeaFileService'
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // Компонент подключения к Redis или его конфиг
            'tableName' => '{{%queue}}',
            'channel' => 'queue', // Ключ канала очереди
            'mutex' => \yii\mutex\PgsqlMutex::class, // Mutex that used to sync queries
            'mutexTimeout' => 0,
            'as log' => \yii\queue\LogBehavior::class
        ],
    ],
];
