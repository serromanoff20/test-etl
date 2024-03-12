<?php namespace app\models;

include_once '/data/app/db/Connect.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use app\db\Connect;
use PDO;

class AllView extends Model
{
    public int $id_agency;
    public string $local_id;
    public string $name_agency;
    public int $id_estate;
    public string $address;
    public float $price;
    public string $rooms;
    public string $description;
    public string $floor;
    public string $house_floors;
    public int $id_manager;
    public string $name_manager;
    public int $id_contacts;
    public string $name_seller;
    public string $phones_seller;

    public function getAllView(): array
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM all_view')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getModelContactsByAgency(int $agency_id): array
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id_contacts, name_seller, phones_seller FROM all_view WHERE id_agency = :agency_id");
        $row->bindValue(':agency_id', $agency_id);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По agency_id - " . $agency_id . " контакты не найдены");

            return [];
        }
        return $res;
    }

    public function initIdEstateByAgency(int $id_agency): void
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id_estate FROM all_view WHERE id_agency = :id_agency");
        $row->bindValue(':id_agency', $id_agency);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По agency_id - " . $id_agency . " объявление не найдено");
        } else {
            $this->id_estate = $res['id_estate'];
        }
    }

    public function initIdEstateByLocalId(string $local_id): void
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id_estate FROM all_view WHERE local_id = :local_id");
        $row->bindValue(':local_id', $local_id);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По local_id - " . $local_id . " объявление не найдено");
        } else {
            $this->id_estate = $res['id_estate'];
        }
    }

    public function initIdContactsByLocalId(string $local_id): void
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id_contacts FROM all_view WHERE local_id = :local_id");
        $row->bindValue(':local_id', $local_id);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По local_id - " . $local_id . " объявление не найдено");
        } else {
            $this->id_contacts = $res['id_contacts'];
        }
    }

    public function initIdManagerByLocalId(string $local_id): void
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT id_manager FROM all_view WHERE local_id = :local_id");
        $row->bindValue(':local_id', $local_id);
        $row->execute();

        $res = $row->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По local_id - " . $local_id . " объявление не найдено");
        } else {
            $this->id_manager = $res['id_manager'];
        }
    }

    public function getEstateByManagerName(string $name): array
    {
        $connect = new Connect();
        $row = $connect->connection->prepare("SELECT * FROM all_view WHERE name_manager = :name_manager");
        $row->bindValue(':name_manager', $name);
        $row->execute();

        $res = $row->fetchAll(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->setError(get_called_class(), "По manager_id - " . $name . " имущество не найдено");

            return [];
        }

        return $res;
    }
}