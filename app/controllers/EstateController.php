<?php namespace app\controllers;

include_once '/data/app/models/Estate.php';
include_once '/data/app/models/responses/Response.php';

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
}