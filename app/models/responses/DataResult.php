<?php namespace app\models\responses;


class DataResult
{
    public bool $isException = false;

    public array $data;

    public string $message;

    /**
     * DataResult constructor.
     * @param bool $isExc
     * @param array $data
     * @param string $message
     */
    public function __construct(array $data, string $message, bool $isExc=false)
    {
        $this->isException = $isExc;
        $this->data = $data;
        $this->message = $message;
    }
}