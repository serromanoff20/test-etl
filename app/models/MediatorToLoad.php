<?php namespace app\models;

include_once "/data/app/models/Manager.php";
include_once "/data/app/models/Contact.php";
include_once "/data/app/models/Estate.php";

use app\models\Agency;
use app\models\Manager;
use app\models\Contact;
use app\models\Estate;

class MediatorToLoad extends Model
{
    private array $res_agency = [];
    private array $res_manager = [];
    private array $res_contacts = [];

    public function toLoad(array $data): array
    {
        $modelAgency = new Agency();
        $data_for_agency = [];
        $modelManager = new Manager();
        $data_for_manager = [];
        $modelContact = new Contact();
        $data_for_contacts = [];
        $modelEstate = new Estate();
        $data_for_estate = [];

        for ($i = 0; $i < count($data['id']); $i++) {
            $data_for_agency[$data['id'][$i]] = $data['Агенство Недвижимости'][$i];
            $data_for_manager[$data['id'][$i]] = $data['Менеджер'][$i];
            $data_for_contacts[$data['id'][$i]] = [
                'name' => $data['Продавец'][$i],
                'phones' => $data['Телефоны продавца'][$i]
            ];
            $data_for_estate[$data['id'][$i]] = [
                'address' => $data['Адрес'][$i],
                'price' => preg_replace( '/\s+/' , '' , $data['Цена'][$i]),
                'rooms' => $data['Комнат'][$i],
                'floor' => $data['Этаж'][$i],
                'house_floors' => $data['Этажей'][$i],
                'description' => $data['Описание'][$i],
                'contact_id' => 0,
                'manager_id' => 0,
            ];
        }

        $this->res_agency = $modelAgency->toLoadDataInAgencyModel($data_for_agency);
        if ($modelAgency->hasErrors()) {
            $this->setError(get_class($modelAgency), 'Не получилось создать Agency');

            return [];
        }

        $input_param_manager = [];
        foreach ($data_for_manager as $key => $value) {
            $input_param_manager[$this->res_agency[$key]] = $value;
        }
        $this->res_manager = $modelManager->toLoadDataInManagerModel($input_param_manager);
        if ($modelManager->hasErrors()) {
            $this->setError(get_class($modelManager), 'Не получилось создать Manager');

            return [];
        }

        $this->res_contacts = $modelContact->toLoadDataInContactModel($data_for_contacts);
        if ($modelContact->hasErrors()) {
            $this->setError(get_class($modelContact), 'Не получилось создать Contact');

            return [];
        }

        $input_param_estate = [];
        foreach ($data_for_estate as $local_id => $item) {
            $input_param_estate[$local_id] = $item;
            $input_param_estate[$local_id]['contact_id'] = (int)$this->res_contacts[$local_id];
            $input_param_estate[$local_id]['manager_id'] = (int)$this->res_manager[$this->res_agency[$local_id]];
        }
        $result = $modelEstate->toLoadDataInEstateModel($input_param_estate);
        if ($modelEstate->hasErrors()) {
            $this->setError(get_class($modelEstate), 'Не получилось создать Estate');

            return [];
        }

        return !empty($result) ? ['message' => "Данные загружены"] : [];
    }
}