<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace freizeitkalender\eu;

/**
 * Description of IntValidator
 *
 * @author philipp
 */
class LimitValidator
{
    //put your code here

    /**
     *
     * @var int|null
     */
    private $limit;

    /**
     * @var MessageBucket
     */
    private $messageBucket;

    /**
     *
     * @var bool
     */
    private $isValid = false;

    /**
     *
     * @param string $inputValue
     */
    public function __construct(string $inputValue)
    {
        $this->messageBucket = new MessageBucket();
        $limit = (int)$inputValue;
        if ($limit <= 0 || $limit > 250) {
            $this->getMessageBucket()->addMessageItem(ErrorLevelEnum::ERROR(), 'Ungültigen Wert eingeben. Er sollte zwischen 1 und 250 liegen.');
            return;
        }

        if (!$this->getMessageBucket()->hasErrorMessage()) {
            $this->setIsValid(true);
            $this->setLimit($limit);
        }
    }

    /**
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $istValid
     */
    private function setIsValid(bool $istValid): void
    {
        $this->isValid = $istValid;
    }

    /**
     * @param int $limit
     */
    private function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return MessageBucket
     */
    public function getMessageBucket(): MessageBucket
    {
        return $this->messageBucket;
    }

}
