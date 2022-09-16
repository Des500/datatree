<?php

class DB
{
    private static $_db = null;
    static function getInstence() {
        self::$_db = null;
        if(self::$_db == null) {
            $user='root';
            $pass='root';
            $db='testdatatree';
            $host='localhost';
            $dsn = 'mysql:host='.$host.';dbname='.$db;
            self::$_db = new PDO($dsn, $user, $pass);
            return self::$_db;
        }
    }
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}