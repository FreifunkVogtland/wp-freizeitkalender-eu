<?php


namespace freizeitkalender\eu;


class CalendarFilterInfo
{
    /**
     * @var string
     */
    private $filterName = '';

    /**
     * @var int
     */
    private $eventCount = 0;

    /**
     * @var MessageItem
     */
    private $messageItem;

    /**
     * @return string
     */
    public function getFilterName(): string
    {
        return $this->filterName;
    }

    /**
     * @return int
     */
    public function getEventCount(): int
    {
        return $this->eventCount;
    }

    /**
     * @param string $filterName
     */
    public function setFilterName(string $filterName): void
    {
        $this->filterName = $filterName;
    }

    /**
     * @param int $eventCount
     */
    public function setEventCount(int $eventCount): void
    {
        $this->eventCount = $eventCount;
    }

    /**
     * @return MessageItem
     */
    public function getMessageItem(): MessageItem
    {
        return clone $this->messageItem;
    }

    /**
     * @param ErrorLevelEnum $errorLevelEnum
     * @param string $message
     */
    public function setMessageItem(ErrorLevelEnum $errorLevelEnum, string $message): void
    {
        $this->messageItem = new MessageItem($errorLevelEnum, $message);
    }

}