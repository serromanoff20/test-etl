<?php namespace app\models;

include_once '/data/app/models/ErrorModel.php';

use app\models\ErrorModel;

class Model
{
    private static array $errors = [];

    public function setError(string $placeError, string $textError): void
    {
        $error = new ErrorModel($placeError, $textError);

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