<?php namespace app\db;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once '/data/vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;

class Connect
{
  public object $connection;

  public function __construct() {
      $dotenv = Dotenv::createImmutable('/data/');
      $dotenv->load();

      $this->connection = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'] . '', $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
  }
}
