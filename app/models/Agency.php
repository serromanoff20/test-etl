<?php namespace app\models;

include_once '/data/app/db/Connect.php';

use app\db\Connect;
use PDO;

class Agency
{
    public int $id;

    public string $name;

    public function getOne(int $id)//: self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM agency WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->name = $row['name'];

        return $this;
    }

    public function getAll(): array
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM agency')->fetchAll(PDO::FETCH_ASSOC);
    }
}