<?php namespace app\controllers;

include_once '/data/app/models/Contact.php';
include_once '/data/app/models/AllView.php';
include_once '/data/app/models/responses/Response.php';

use app\models\Contact;
use app\models\AllView;
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
    public function actionGetContactsByAgency(string $agency_id=null): string
    {
        $response = new Response();

        try {
            if (is_null($agency_id)) {
                return $response->getModelErrors(['message' => "Неверно переданы параметры"]);
            }

            $model = new AllView();

            $result = $model->getModelContactsByAgency($agency_id);

            if(empty($result)) {
                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }
    }

    public function actionGetContactsByLocalIdAgency(string $local_id): string
    {
        $response = new Response();

        try {
            $modelAllView = new AllView();

            $modelAllView->initIdContactsByLocalId($local_id);

            if ($modelAllView->hasErrors() && !isset($modelAllView->id_contacts)) {

                return $response->getModelErrors($modelAllView->getErrors());
            } else {
                $model = new Contact();

                $result = $model->getOne($modelAllView->id_contacts);
            }
            if ($model->hasErrors()) {
                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }
//in development--
}