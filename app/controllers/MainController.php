<?php namespace app\controllers;

include_once '/data/app/models/responses/Response.php';
include_once '/data/app/models/Agency.php';
include_once '/data/app/models/Excel.php';

use app\models\responses\Response;
use app\models\Agency;
use app\models\Excel;
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
            } else {
                return $response->getSuccess($model->getOne($id));
            }
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
            $modelLoad = new Excel();

            $modelLoad->loading();

            $modelLoad->parser();

            return $response->getSuccess("ПРОВЕРКА");
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
