<?php


namespace freizeitkalender\eu;


class MessageItem
{

    /**
     * @var ErrorLevelEnum
     */
    private $errorLevelEnum;

    /**
     * @var string
     */
    private $message;

    public function __construct(ErrorLevelEnum $errorLevelEnum, string $message)
    {
        $this->errorLevelEnum = $errorLevelEnum;
        $this->message = $message;
    }

    /**
     * @return ErrorLevelEnum
     */
    public function getErrorLevelEnum(): ErrorLevelEnum
    {
        return $this->errorLevelEnum;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}