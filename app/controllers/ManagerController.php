<?php namespace app\controllers;

include_once '/data/app/models/Manager.php';
include_once '/data/app/models/responses/Response.php';

use app\models\Manager;
use app\models\responses\Response;
use Exception;

class ManagerController
{
    /**
     * Gets everyone managers of agency or one manager. Depend from input param.
     * @param string|null $id
     *
     * @return string
     */
    public function actionGetManager(string $id=null): string
    {
        $response = new Response();

        try {
            $model = new Manager();

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

    public function actionGetManagersByAgency(string $agency_id=null): string
    {
        $response = new Response();

        try {
            if (is_null($agency_id)) {
                return $response->getModelErrors(['message' => "Неверно переданы параметры"]);
            }

            $model = new Manager();

            $result = $model->getByAgency($agency_id);

            if(empty($result)) {
                return $response->getModelErrors($model->getErrors());
            }

            return $response->getSuccess($result);
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }
    }
}