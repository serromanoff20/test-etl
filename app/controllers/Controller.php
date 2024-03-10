<?php namespace app\controllers;

class Controller
{
    public function definedParams(string $action, array $params): array
    {
//on futures++
//        switch ($action):
//            case 'all':
//                echo $controller->actionGetAgency();
//                break;
//            case 'by-id':
//                echo $controller->actionGetAgency($_GET['id']);
//                break;
//            case 'by-local-id':
////                    (!isset($_GET['local_id'])) ? break : continue;
//                echo $controller->actionGetAgencyByLocalId(isset($_GET['local_id']));
//                break;
//            default:
//                echo(json_encode($_GET) . "\n");
//on futures--

        return $params;
    }
}