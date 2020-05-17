<?php


namespace freizeitkalender\eu;


class MessageBucket
{

    /**
     *
     * @var array
     */
    private $messageList = [];

    /**
     * @param ErrorLevelEnum $errorLevelEnum
     * @param string $message
     */
    public function addMessageItem(ErrorLevelEnum $errorLevelEnum, string $message): void
    {
        $this->messageList[] = new MessageItem($errorLevelEnum, $message);
    }

    /**
     * @param ErrorLevelEnum $errorLevelEnum
     * @param string $message
     */
    public function addMessage(ErrorLevelEnum $errorLevelEnum, string $message): void
    {
        $this->addMessageItem($errorLevelEnum, $message);
    }

    /**
     * @return MessageItem[]
     */
    public function getMessageList(): array
    {
        return $this->messageList;
    }

    /**
     * @return bool
     */
    public function hasErrorMessage(): bool
    {
        foreach ($this->getMessageList() as $messageItem) {
            if ($messageItem->getErrorLevelEnum()->getValue() === ErrorLevelEnum::ERROR()->getValue()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $messageList
     */
    public function appendMessageList(array $messageList): void
    {
        foreach ($messageList as $messageItem) {
            if ($messageItem instanceof MessageItem) {
                $this->addMessageItem($messageItem->getErrorLevelEnum(), $messageItem->getMessage());
            }
        }
    }
}
