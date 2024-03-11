<?php namespace app\models;

include_once '/data/app/db/Connect.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use app\db\Connect;
use app\models\responses\Response;
use constants\Constants;
use PDO;

class Manager extends Model
{
    public int $id;

    public ?string $name;

    public int $agency_id;

    public function getOne(int $id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM manager WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setError(get_called_class(), "По id - " . $id . " менеджер не найден");

            return null;
        }

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->agency_id = $row['agency_id'];

        return $this;
    }

    public function getByAgency(int $agency_id): array
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM manager WHERE agency_id = :agency_id");
        $row->bindValue(':agency_id', $agency_id);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По agency_id - " . $agency_id . " менеджеры не найдены");

            return [];
        }
        return $res;
    }

    public function getAll(): array
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM manager')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toLoadDataInManagerModel(array $id_and_name): array
    {
        $result = [];
        $connect = new Connect();
        try {
            $stmt = $connect->connection->prepare("INSERT INTO manager (name, agency_id) VALUES (?, ?)");

            foreach($id_and_name as $agency_id => $name_manager) {
                $isExist = $this->checkOnExistedRow($name_manager, $agency_id);
                if ($isExist === false) {
                    $stmt->bindParam(1, $name_manager);
                    $stmt->bindParam(2, $agency_id);

                    $result[$agency_id] = ($stmt->execute()) ? $this->getId($name_manager, $agency_id) : false;
                } else {
                    $result[$agency_id] = $this->getId($name_manager, $agency_id);
                }
            }
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());
        } finally {
            return $result;
        }

    }

    private function checkOnExistedRow(string $name_manager, string $agency_id): bool
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM manager WHERE name = :name_manager and agency_id = :agency_id");
        $row->execute([':name_manager' => $name_manager, ':agency_id' => $agency_id]);
        $res = $row->fetchAll();

        return !!$res;
    }

    private function getId(string $name_manager, string $agency_id): int
    {
        $connect = new Connect();
        $row = $connect->connection->prepare('SELECT id FROM manager WHERE name = :name_manager and agency_id = :agency_id');
        $row->execute([':name_manager' => $name_manager, ':agency_id' => $agency_id]);
        $res = $row->fetchAll(PDO::FETCH_ASSOC);

        return $res[0]['id'];
    }
}