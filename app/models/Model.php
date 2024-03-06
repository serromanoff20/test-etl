<?php namespace app\models;

include_once '/data/app/models/ErrorModel.php';
//include_once '/data/app/models/Model.php';
//
//use app\models\Model;
use app\models\ErrorModel;

class Model
{
    private static array $errors = [];

    public function setError(string $placeError, string $textError): void
    {
        $error = new ErrorModel($placeError, $textError);

//        if (!empty(self::$errors)) {
//            print_r(self::$errors);
//        }
//        echo "\n";
        self::$errors = array_merge(self::$errors, [$error]);
    }

    public function getErrors(): array
    {
//        print_r(self::$errors); //debugging
        return self::$errors;
    }

    public function hasErrors(): bool
    {
//        print_r(self::$errors); //debugging

        return !empty(self::$errors);
    }
}