<?php

if (strpos($_SERVER['SERVER_NAME'], 'printline.sk')) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=' . ((strpos($_SERVER['SERVER_NAME'], 'printline.sk')) ? 'localhost;unix_socket=/tmp/mariadb55.sock' : 'mariadb55.websupport.sk:3310') . ';dbname=zero_master',
        'username' => 'zero_master',
        'password' => 'Ra9*"MZ+op',
//    'dsn' => 'mysql:host=localhost;dbname=ccwz0d43',
//    'username' => 'root',
//    'password' => '',
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 600,
        'schemaCache' => 'cache',
    ];
} else {
    return [
        'class' => 'yii\db\Connection',
        // 'dsn' => 'mysql:host=' . ((strpos($_SERVER['SERVER_NAME'], 'printline.sk')) ? 'localhost;unix_socket=/tmp/mariadb55.sock' : 'mariadb55.websupport.sk:3310') . ';dbname=ccwz0d43',
        //'username' => 'ccwz0d43',
        //'password' => '7kF4JMFguc',
        'dsn' => 'mysql:host=localhost;dbname=zero.master',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 600,
        'schemaCache' => 'cache',
    ];
}


