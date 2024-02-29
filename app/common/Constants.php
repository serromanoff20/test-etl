<?php namespace constants;

class Constants
{
    /**
     * Путь к обрабатываемым фалам
     */
    public const PATH_FILES = "/data/files/";

    /**
     * Тип ответа - "ошибка"
     */
    public const ERROR_TYPE = "error";

    /**
     * Тип ответа - "исключение"
     * Исключения, как могут содержать результирующий набор данных(с предупреждением),
     * так и могут содержать информацию об ошибке
     */
    public const EXCEPTION_TYPE = "exception";

    /**
     * Тип ответа - "успешно"
     */
    public const SUCCESS_TYPE = "success";
}