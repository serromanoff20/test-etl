<?php namespace app\models;

include_once '/data/app/db/Connect.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use app\db\Connect;
use app\models\responses\Response;
use common\Constants;
use PDO;

class Contact extends Model
{
    public int $id;

    public ?string $name;

    public string $phones;


    public function getOne(int $id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM contacts WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setError(get_called_class(), "По id - " . $id . " контакт не найден");

            return null;
        }

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->phones = $row['phones'];

        return $this;
    }
//
//    public function getByLocalId(string $local_id): ?self
//    {
//        $connect = new Connect();
//        $row = $connect->connection->prepare("SELECT * FROM agency WHERE local_id = :local_id");
//        $row->bindValue(':local_id', $local_id);
//        $row->execute();
//
//        if (!$row->execute()) {
//            $this->setError(get_called_class(), "По local_id - " . $local_id . " агенство не найдено");
//
//            return null;
//        }
//        $row->fetch(PDO::FETCH_ASSOC);
//
//        $this->id = $row['id'];
//        $this->local_id = $row['local_id'];
//        $this->name = $row['name'];
//
//        return $this;
//    }

    public function getAll(): array
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM contacts')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toLoadDataInContactModel(array $name_and_phones): array
    {
        $result = [];
        $connect = new Connect();
        try {
            $stmt = $connect->connection->prepare("INSERT INTO contacts (name, phones) VALUES (?, ?)");

            foreach($name_and_phones as $key => $item) {
                $name_seller = $item['name'];
                $phones_seller = $item['phones'];
                $isExist = $this->checkOnExistedRow($name_seller, $phones_seller);
                if ($isExist === false) {
                    $stmt->bindParam(1, $name_seller);
                    $stmt->bindParam(2, $phones_seller);

                    $result[$key] = ($stmt->execute()) ? $this->getId($name_seller, $phones_seller) : false;
                } else {
                    $result[$key] = $this->getId($name_seller, $phones_seller);
                }
            }
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());
        } finally {
            return $result;
        }

    }

    private function checkOnExistedRow(string $name_seller, string $phones_seller): bool
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM contacts WHERE name = :name and phones = :phones");
        $row->execute([':name' => $name_seller, ':phones' => $phones_seller]);
        $res = $row->fetchAll();

        return !!$res;
    }

    private function getId(string $name_seller, string $phones_seller): int
    {
        $connect = new Connect();
        $row = $connect->connection->prepare('SELECT id FROM contacts WHERE name = :name and phones = :phones');
        $row->execute([':name' => $name_seller, ':phones' => $phones_seller]);
        $res = $row->fetchAll(PDO::FETCH_ASSOC);

        return $res[0]['id'];
    }
}