<?php
namespace asc {
    class PDO_Conn
    {
        public static $instance = null;

        private static $host = '127.0.0.1';
        private static $db   = 'stm_asc';
        private static $charset = 'utf8mb4';
        private static $user = 'alekrabbe';
        private static $pass = 'jmu_stm@ceinf';

        private static $dsn = '';

        private function __construct() {
            //
        }

        public static function getInstance() {
            if (self::$instance === null) {
                try {
                    self::$dsn = "mysql:host=".self::$host.";dbname=".self::$db.";charset=".self::$charset.";";
                    self::$instance = new \PDO(self::$dsn, self::$user, self::$pass);
                    self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    self::$instance->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
//                    echo "Connected successfully";
                } catch(\PDOException $e) {
//                    echo "Connection failed: " . $e->getMessage();
                }
            }
            return self::$instance;
        }
    }
}







