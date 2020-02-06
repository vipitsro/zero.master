<?php

return [
    'class' => 'yii\db\Connection',
    //'dsn' => 'mysql:host='.((strpos($_SERVER['SERVER_NAME'],'printline.sk'))? 'localhost;unix_socket=/tmp/mariadb55.sock':'mariadb55.websupport.sk:3310').';dbname=zero.master',
    //'username' => 'root',
    //'password' => '',
    'dsn' => 'mysql:host=localhost;dbname=zero.master',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 600,
    'schemaCache' => 'cache',
];
