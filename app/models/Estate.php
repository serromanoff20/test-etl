<?php namespace app\models;

include_once '/data/app/db/Connect.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use app\db\Connect;
use app\models\responses\Response;
use common\Constants;
use PDO;

class Estate extends Model
{
    public int $id;
    public ?string $address;
    public ?int $rooms;
    public ?int $floor;
    public ?int $house_floors;
    public ?string $description;
    public int $contact_id;
    public int $manager_id;


    public function getOne(int $id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM estate WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setError(get_called_class(), "По id - " . $id . " имущество не найдено");

            return null;
        }

        $this->id = $row['id'];
        $this->address = $row['address'];
        $this->rooms = $row['rooms'];
        $this->floor = $row['floor'];
        $this->house_floors = $row['house_floors'];
        $this->description = $row['description'];
        $this->contact_id = $row['contact_id'];
        $this->manager_id = $row['manager_id'];

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
        return $connect->connection->query('SELECT * FROM estate')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toLoadDataInEstateModel(array $input_param): array
    {
        $result = [];
        $connect = new Connect();
        try {
            $stmt = $connect->connection->prepare("INSERT INTO estate (address, price, rooms, floor, house_floors, description, contact_id, manager_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            foreach($input_param as $key => $item) {
                $address = $item['address'];
                $price = (double)$item['price'];
                $rooms = $item['rooms'];
                $floor = $item['floor'];
                $house_floors = $item['house_floors'];
                $description = $item['description'];
                $contact_id = (int)$item['contact_id'];
                $manager_id = (int)$item['manager_id'];

                $isExist = $this->checkOnExistedRow($address, $manager_id);
                if ($isExist === false) {
                    $stmt->bindParam(1, $address);
                    $stmt->bindParam(2, $price);
                    $stmt->bindParam(3, $rooms);
                    $stmt->bindParam(4, $floor);
                    $stmt->bindParam(5, $house_floors);
                    $stmt->bindParam(6, $description);
                    $stmt->bindParam(7, $contact_id);
                    $stmt->bindParam(8, $manager_id);

                    $result[$key] = ($stmt->execute()) ? 1 : 0;
                } else {
                    $result[$key] = 1;
                }
            }
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());
        } finally {
            return $result;
        }

    }

    private function checkOnExistedRow(string $address, int $manager_id): bool
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM estate WHERE address = :address and manager_id = :manager_id");
        $row->execute([':address' => $address, ':manager_id' => $manager_id]);
        $res = $row->fetchAll();

        return !!$res;
    }

    private function getId(string $address, int $manager_id): int
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id FROM estate WHERE address = :address and manager_id = :manager_id");
        $row->execute([':address' => $address, ':manager_id' => $manager_id]);
        $res = $row->fetchAll(PDO::FETCH_ASSOC);

        return $res[0]['id'];
    }
}