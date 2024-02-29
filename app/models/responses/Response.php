<?php namespace app\models\responses;

include_once '/data/app/models/responses/DataResult.php';
include_once '/data/app/common/Constants.php';

use app\models\responses\DataResult;
use constants\Constants;
use Exception;

class Response
{
    /**
     * Resulting data set
     *
     * @var DataResult
     */
    public DataResult $response;

    /**
     * HTTP-code of response
     *
     * @var int
     */
    public int $code;

    /**
     * Handler messages about success response
     * @param mixed $data
     * @param int $code
     *
     * @return string
     */
    public function getSuccess($data, int $code = 200): string
    {
        try {
            $this->response = new DataResult((array)$data, Constants::SUCCESS_TYPE);
            $this->code = $code;

            return json_encode($this, JSON_UNESCAPED_UNICODE);
        } catch (Exception $exception) {

            return $this->getExceptionError($exception, 203);
        }
    }

//    /**
//     * Получение массива сообщений об ошибках из ошибок модели
//     *
//     * @param array $errors
//     * @param int $code
//     * @return string
//     */
//    public function getModelErrors(array $errors, int $code = 500): string
//    {
//        try {
//            $arrOut = [];
//
//            if (count($errors) === 0) {
//                return json_encode([], JSON_UNESCAPED_UNICODE);
//            }
//
//            foreach ($errors as $error_arr) {
//                $error_arr = (gettype($error_arr) === 'array') ? $error_arr : array($error_arr);
//
//                $arrOut = array_merge($arrOut, $error_arr);
//                $this->getError($arrOut, $code);
//            }
//
//            $is_json = json_encode($this, JSON_UNESCAPED_UNICODE);
//
//            if (!$is_json) {
//                return json_encode($this, JSON_THROW_ON_ERROR);
//            }
//
//            return $is_json;
//        } catch (Exception $exception) {
//            return $this->getExceptionError($exception, $code);
//        }
//    }

//    /**
//     * Сообщение об ошибке
//     * @param array $error
//     * @param int $code
//     *
//     * @return void
//     */
//    protected function getError(array $error, int $code): void
//    {
//        $key_error = array_key_first($error);
//        if (gettype($key_error) === 'string') {
//            $this->response->message = $error[$key_error][0] . " - " . $key_error;
//        } else {
//            $this->response->data = $error;
//        }
//        $this->code = $code;
////        $this->type = self::ERROR_TYPE;
//    }

    /**
     * Handler Exception
     * @param Exception $exception
     * @param int $code
     *
     * @return string
     */
    public function getExceptionError(Exception $exception, int $code = 500): string
    {
        $this->response = new DataResult($exception->getTrace(), $exception->getMessage(), true);
        $this->code = $code;

        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
}