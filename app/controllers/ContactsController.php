<?php namespace app\controllers;

include_once '/data/app/models/Contact.php';
include_once '/data/app/models/responses/Response.php';

use app\models\Contact;
use app\models\responses\Response;
use Exception;

class ContactsController
{
    /**
     * Gets everyone contacts of sell or contacts one seller. Depend from input param.
     * @param string|null $id
     *
     * @return string
     */
    public function actionGetContacts(string $id=null): string
    {
        $response = new Response();

        try {
            $model = new Contact();

            $id = !empty($id) ? (int)$id : null;
            if (is_null($id)) {

                return $response->getSuccess($model->getAll());
            }

            $result = $model->getOne($id);
            if (is_null($result) && $model->hasErrors()) {

                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }
//in development++
//    public function actionGetContactsByAgency(string $agency_id=null): string
//    {
//        $response = new Response();
//
//        try {
//            if (is_null($agency_id)) {
//                return $response->getModelErrors(['message' => "Неверно переданы параметры"]);
//            }
//
//            $model = new Contact();
//
//            $result = $model->getByAgency($agency_id);
//
//            if(empty($result)) {
//                return $response->getModelErrors($model->getErrors());
//            }
//
//            return $response->getSuccess($result);
//        } catch (Exception $exception) {
//            return $response->getExceptionError($exception);
//        }
//    }
//in development--
}