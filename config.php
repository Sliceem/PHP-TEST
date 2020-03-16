<?php
//Database configuration data
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'web_db');

//usefull hints
define('BR', '</br>');

$GLOBALS['config'] = [
    'mysql' => [
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'database'  => 'web_db'
    ],
    'url' => 'localhost'
];

class Config{
    public static function get($path = null){
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('.', $path);

            foreach ($path as $item){
                if (isset($config[$item])){
                    $config = $config[$item];
                }
            }
            return $config;
        }
        return false;
    }
}