<?php namespace app;

header("Access-Control-Allow-Origin: *");

include_once 'controllers/MainController.php';
include_once 'controllers/AgencyController.php';
include_once 'controllers/ManagerController.php';
include_once 'controllers/ContactsController.php';
include_once 'controllers/EstateController.php';

use app\controllers\MainController;
use app\controllers\AgencyController;
use app\controllers\ManagerController;
use app\controllers\ContactsController;
use app\controllers\EstateController;
//use Exception;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class App
{
    public function main(): void
    {
        $path = explode("/", $_SERVER['REQUEST_URI'], 3);
        $path_to_controller = $path[1];
        $path_to_action = isset($path[2]) ? explode("?", $path[2], 2) : null;

        if (
            ($_SERVER['REQUEST_METHOD'] === 'POST')
            && isset($_FILES["file"])
        ) {
            echo (new MainController())->actionLoadingData();
        } elseif (
            ($_SERVER['REQUEST_METHOD'] === 'GET')
            && $path_to_controller === 'agency'
        ) {
            $controller = new AgencyController();

//            $params = $controller->definedParams($path_to_action[0], $_GET); //on futures

            switch ($path_to_action[0]) {
                case 'all':
                    echo $controller->actionGetAgency();
                    break;
                case 'by-id':
                    echo $controller->actionGetAgency($_GET['id']);
                    break;
                case 'by-local-id':
                    echo $controller->actionGetAgencyByLocalId($_GET['local_id']);
                    break;
//                default:
//                    echo(json_encode($_GET) . "\n"); //debugging
            }
        } elseif (
            ($_SERVER['REQUEST_METHOD'] === 'GET')
            && $path_to_controller === 'manager'
        ){
            $controller = new ManagerController();

            switch ($path_to_action[0]) {
                case 'all':
                    echo $controller->actionGetManager();
                    break;
                case 'by-id':
                    echo $controller->actionGetManager($_GET['id']);
                    break;
                case 'by-agency':
                    echo $controller->actionGetManagersByAgency($_GET['agency_id']);
                    break;
                case 'by-local-id-agency':
                    echo $controller->actionGetManagerByLocalIdAgency($_GET['local_id']);
                    break;
//                default:
//                    echo(json_encode($_GET) . "\n"); //debugging
            }
        } elseif (
            ($_SERVER['REQUEST_METHOD'] === 'GET')
            && $path_to_controller === 'contacts'
        ){
            $controller = new ContactsController();

            switch ($path_to_action[0]) {
                case 'all':
                    echo $controller->actionGetContacts();
                    break;
                case 'by-id':
                    echo $controller->actionGetContacts($_GET['id']);
                    break;
                case 'by-agency':
                    echo $controller->actionGetContactsByAgency($_GET['agency_id']);
                    break;
                case 'by-local-id-agency':
                    echo $controller->actionGetContactsByLocalIdAgency($_GET['local_id']);
                    break;
//                default:
//                    echo(json_encode($_GET) . "\n"); //debugging
            }
        } elseif (
            ($_SERVER['REQUEST_METHOD'] === 'GET')
            && $path_to_controller === 'estate'
        ){
            $controller = new EstateController();

            switch ($path_to_action[0]) {
                case 'all':
                    echo $controller->actionGetEstate();
                    break;
                case 'all-view':
                    echo $controller->actionGetAllView();
                    break;
                case 'by-id':
                    echo $controller->actionGetEstate($_GET['id']);
                    break;
                case 'by-agency':
                    echo $controller->actionGetEstateByAgency($_GET['agency_id']);
                    break;
                case 'by-local-id-agency':
                    echo $controller->actionGetEstateByLocalIdAgency($_GET['local_id']);
                    break;
                case 'by-contacts-id':
                    echo $controller->actionGetEstateByContact($_GET['contact_id']);
                    break;
                case 'by-manager-id':
                    echo $controller->actionGetEstateByManager($_GET['manager_id']);
                    break;
//                default:
//                    echo $controller->actionCheck(); //debugging
            }
        }

        exit();
    }
}

(new App())->main();