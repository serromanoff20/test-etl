<?php namespace app\controllers;

include_once '/data/app/models/responses/Response.php';
include_once '/data/app/models/Agency.php';
include_once '/data/app/models/Excel.php';
include_once '/data/app/models/MediatorToLoad.php';

use app\models\responses\Response;
use app\models\Agency;
use app\models\Excel;
use app\models\MediatorToLoad;
use constants\Constants;
use Exception;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class MainController
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
     * @param string|null $local_id
     *
     * @return string
     */
    public function actionGetAgencyByLocalId(string $local_id): string
    {
        $response = new Response();

        try {
            $model = new Agency();

            $local_id = !empty($local_id) ? (int)$local_id : null;
            if (is_null($local_id)) {
                return $response->getSuccess($model->getAll());
            }
            //todo: Возвратить модель обработчика ошибок
            $result = $model->getByLocalId($local_id);
            if (is_null($result)) {
                return $response->getSuccess('');
            }
            return $response->getSuccess($result);
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }
    }

    public function actionLoadingData(): string
    {
        $response = new Response();

        try {
            $modelLoad = new Excel();

            if ($modelLoad->initFile()) {
                $parsedData = $modelLoad->parser();

                $modelMediator = new MediatorToLoad();

                $forLoading = $modelMediator->toLoad($parsedData);
                //todo: Возвратить модель обработчика ошибок
                return $response->getSuccess($forLoading);

//                if (is_null($forLoading)) {
//                    return $response->getModelErrors($modelMediator->)
//                }
            }
            //todo: Возвратить модель обработчика ошибок
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }
    }

    public function actionSayHello(string $str): string
    {
        return '<h1>Привет, '.$str.'</h1>';
    }

    public function actionCheck(): string
    {
        $response = new Response();

        try {
            return $response->getSuccess('CHECK');
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }
    }

//    public function get()
//    {
//        echo (new Main())->getFunction();
//    }
//
//    public function post(string $key)
//    {
//        echo (new Main())->postFunction($key);
//    }
//
//    public function sayBuy()
//    {
//        echo (new Main())->error();
//    }

}
