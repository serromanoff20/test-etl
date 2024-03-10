<?php namespace app\controllers;

include_once '/data/app/controllers/Controller.php';
include_once '/data/app/models/Agency.php';
include_once '/data/app/models/responses/Response.php';

use app\controllers\Controller;
use app\models\Agency;
use app\models\responses\Response;
use Exception;

class AgencyController extends Controller
{
    /**
     * Gets everyone agency or one agency. Depend from input param.
     * @param string|null $id
     *
     * @return string
     */
    public function actionGetAgency(string $id=null): string
    {
        $response = new Response();

        try {
            $model = new Agency();

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
     * Gets one agency by local_id.
     * @param string $local_id
     *
     * @return string
     */
    public function actionGetAgencyByLocalId(string $local_id=null): string
    {
        $response = new Response();

        try {
            $model = new Agency();

            if (is_null($local_id)) {
                return $response->getModelErrors(['message' => "Неверно переданы параметры"]);
            }
            $result = $model->getByLocalId($local_id);
            if (is_null($result)) {
                return $response->getSuccess('');
            }
            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }
}