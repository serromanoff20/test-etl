<?php namespace app\db;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use PDO;

class Connect
{
  public object $connection;

  public function __construct() {
        // Подключение драйвера mysql и коннект с сервером через PDO
        $this->connection = new PDO('mysql:host=192.168.69.159;dbname=sarrc_test_db', 'pc', 'pc@1234');
  }
}
