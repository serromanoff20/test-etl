<?php namespace app;

header("Access-Control-Allow-Origin: *");

include_once 'controllers/MainController.php';

use Exception;
use app\controllers\MainController;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class App
{
    public function main(): void
    {
        $controller = new MainController();

        $path = explode("?", $_SERVER['REQUEST_URI'], 2);

        if (
            ($_SERVER['REQUEST_METHOD'] === 'POST')
            && isset($_FILES["file"])
        ) {
            echo $controller->actionLoadingData();
        } else if (isset($_GET['id']) || isset($_GET['local_id'])) {
            switch ($path[0]) {
                case '/agency/one':
                    echo $controller->actionGetAgency($_GET['id']);
                    break;
                case '/contacts/by-local-id':
                    echo $controller->actionGetAgencyByLocalId($_GET['local_id']);
                    break;
                case '/contacts/one':
                    echo $controller->actionSayHello('надо начинать писать следующий эндпоинт');
                    break;
                default:
                    echo $controller->actionCheck();
            }
        } else {
            switch ($path[0]) {
                case '/agency/all':
                    echo $controller->actionGetAgency();
                    break;
                case '/contacts/all':
                    echo $controller->actionSayHello('надо начинать писать следующий эндпоинт');
                    break;
                default:
                    echo $controller->actionCheck();
            }
        }

        exit();
    }
}

(new App())->main();