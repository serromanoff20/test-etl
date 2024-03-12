<?php namespace app\controllers;

include_once '/data/app/models/Estate.php';
include_once '/data/app/models/responses/Response.php';

use app\models\AllView;
use app\models\Estate;
use app\models\responses\Response;
use Exception;

class EstateController
{
    /**
     * Gets everyone estate or only one estate. Depend from input param.
     * @param string|null $id
     *
     * @return string
     */
    public function actionGetEstate(string $id=null): string
    {
        $response = new Response();

        try {
            $model = new Estate();

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

    /**
     * Gets all_view, in which have all announcement, including data from Estate, Agency, Manager, Contacts
     *
     * @return string
     */
    public function actionGetAllView(): string
    {
        $response = new Response();

        try {
            $model = new AllView();

            $result = $model->getAllView();

            if (is_null($result) || empty($result) || $result === false) {

                return $response->getModelErrors(['message' => "Ошибка базы данных"]);
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

    public function actionGetEstateByAgency(string $agency_id): string
    {
        $response = new Response();

        try {
            $modelAllView = new AllView();

            $id_agency = (int)$agency_id;
            $modelAllView->initIdEstateByAgency($id_agency);

            if ($modelAllView->hasErrors() && !isset($modelAllView->id_estate)) {

                return $response->getModelErrors($modelAllView->getErrors());
            } else {
                $model = new Estate();

                $result = $model->getOne($modelAllView->id_estate);
            }
            if ($model->hasErrors()) {
                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

    public function actionGetEstateByLocalIdAgency(string $local_id): string
    {
        $response = new Response();

        try {
            $modelAllView = new AllView();

            $modelAllView->initIdEstateByLocalId($local_id);

            if ($modelAllView->hasErrors() && !isset($modelAllView->id_estate)) {

                return $response->getModelErrors($modelAllView->getErrors());
            } else {
                $model = new Estate();

                $result = $model->getOne($modelAllView->id_estate);
            }
            if ($model->hasErrors()) {
                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

//    /**
//     * Gets all_view, in which have name_agency like
//     * @param string $name_agency
//     *
//     * @return string
//     */
    public function actionGetEstateByContact(string $contact_id): string
    {
        $response = new Response();

        try {
            $model = new Estate();

            $contact_id = (int)$contact_id;
            $result = $model->getEstateByContacts($contact_id);

            if ($model->hasErrors()) {

                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

    public function actionGetEstateByManager(string $manager_id): string
    {
        $response = new Response();

        try {
            $model = new Estate();

            $manager_id = (int)$manager_id;
            $result = $model->getEstateByManager($manager_id);

            if ($model->hasErrors()) {

                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

    public function actionGetEstateByManagerName(string $name): string
    {
        $response = new Response();

        try {
            $model = new AllView();

            $result = $model->getEstateByManagerName($name);

            if ($model->hasErrors()) {

                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }
}