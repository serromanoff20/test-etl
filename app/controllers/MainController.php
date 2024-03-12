<?php namespace app\controllers;

include_once '/data/app/models/responses/Response.php';
include_once '/data/app/models/Excel.php';
include_once '/data/app/models/MediatorToLoad.php';

use app\models\ErrorModel;
use app\models\responses\Response;
use app\models\Excel;
use app\models\MediatorToLoad;
use Exception;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class MainController
{
    public function actionLoadingData(): string
    {
        $response = new Response();

        try {
            $modelLoad = new Excel();

            if ($modelLoad->initFile()) {
                $parsedData = $modelLoad->parser();

                $modelMediator = new MediatorToLoad();

                $forLoading = $modelMediator->toLoad($parsedData);
                if (empty($forLoading) || $modelMediator->hasErrors()) {

                    return $response->getModelErrors($modelMediator->getErrors());
                }
                return $response->getSuccess($forLoading);
            }
            return $response->getModelErrors($modelLoad->getErrors());
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }
    }

    public function actionErrors(): string
    {
        $errorModel = new ErrorModel(get_called_class(), 'Неверно составлен запрос');

        return (new Response())->getModelErrors([$errorModel]);
    }
}
