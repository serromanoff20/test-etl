<?php namespace app\models;

use app\models\Agency;
class MediatorToLoad
{
    public function toLoad(array $data)//: array
    {
        $modelAgency = new Agency();
        $data_for_agency=[];
        $data_for_manager=[];
        $data_for_contacts=[];
        $data_for_estate=[];

        for ($i=0; $i<count($data['id']); $i++) {
            $data_for_agency[$data['id'][$i]] = $data['Агенство Недвижимости'][$i];
            $data_for_manager[$data['id'][$i]] = $data['Менеджер'][$i];
            $data_for_contacts[$data['id'][$i]] = [
                'name' => $data['Продавец'][$i],
                'phones' => $data['Телефоны продавца'][$i]
            ];
            $data_for_estate[$data['id'][$i]] = [
                'address' => $data['Адрес'][$i],
                'rooms' => $data['Комнат'][$i],
                'floor' => $data['Этаж'][$i],
                'house_floors' => $data['Этажей'][$i],
                'description' => $data['Описание'][$i],
//                'contract_id' => $data['Телефоны продавца'][$i],
//                'manager_id' => $data['Телефоны продавца'][$i],
            ];
        }

//        $result_fr_agency = $modelAgency->toLoadDataInAgencyModel($data_for_agency);
//        if(is_null($result_fr_agency) || empty($result_fr_agency)){
//            return $modelAgency->getErrors();
//        }

        return $data_for_estate;

        return $data_for_agency;
    }
}