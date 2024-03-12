<?php namespace app\models;

include_once '/data/app/db/Connect.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use app\db\Connect;
use PDO;

class Agency extends Model
{
    public int $id;

    public ?string $local_id;

    public ?string $name;

    public function getOne(int $id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM agency WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setError(get_called_class(), "По id - " . $id . " агенство не найдено");

            return null;
        }

        $this->id = $row['id'];
        $this->local_id = $row['local_id'];
        $this->name = $row['name'];

        return $this;
    }

    public function getByLocalId(string $local_id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM agency WHERE local_id = :local_id");
        $row->bindValue(':local_id', $local_id);
        $row->execute();
        $result = $row->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $this->setError(get_called_class(), "По local_id - " . $local_id . " агенство не найдено");

            return null;
        }

        $this->id = $result['id'];
        $this->local_id = $result['local_id'];
        $this->name = $result['name'];

//        echo(gettype($result));
//        echo(($row instanceof \PDOStatement) ? 'true' : 'false');
        return $this;
    }

    public function getAll(): array
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM agency')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toLoadDataInAgencyModel(array $id_and_name): array
    {
        $result = [];
        $connect = new Connect();
        try {
            $stmt = $connect->connection->prepare("INSERT INTO agency (local_id, name) VALUES (?, ?)");

            foreach($id_and_name as $id => $name) {
                $isExist = $this->checkOnExistedRowByLocalId($id);
                if ($isExist === false) {
                    $stmt->bindParam(1, $id);
                    $stmt->bindParam(2, $name);

                    $result[$id] = ($stmt->execute()) ? $this->getIdByLocalId($id) : false;
                } else {
                    $result[$id] = $this->getIdByLocalId($id);
                }
            }
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());
        } finally {
            return $result;
        }

    }

    private function checkOnExistedRowByLocalId(string $local_id): bool
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM agency WHERE local_id = :local_id");
        $row->execute([':local_id' => $local_id]);
        $res = $row->fetchAll();

        return !!$res;
    }

    private function getIdByLocalId(string $local_id): int
    {
        $connect = new Connect();
        $row = $connect->connection->prepare('SELECT id FROM agency WHERE local_id = :local_id');
        $row->execute([':local_id' => $local_id]);
        $res = $row->fetchAll(PDO::FETCH_ASSOC);

        return $res[0]['id'];
    }
}