<?php

namespace freizeitkalender\eu;

class MessageService
{

    /**
     * @return string
     */
    public static function renderMessage(): string
    {
        $messageHtml = '';
        $messageBucket = self::loadMessageBucket();
        foreach ($messageBucket->getMessageList() as $messageItem) {
            $messageHtml .= '<div class="notice notice-' . PluginService::stripShortname($messageItem->getErrorLevelEnum()->getValue()) . ' is-dismissible"><p>' . esc_html($messageItem->getMessage()) . '</p></div>';
        }
        self::saveMessageBucket(new MessageBucket());

        return $messageHtml;
    }

    /**
     * @param MessageBucket $messageBucket
     */
    public static function saveMessageBucket(MessageBucket $messageBucket): void
    {
        $messageBucketData = [];
        foreach ($messageBucket->getMessageList() as $messageItem) {
            $messageBucketData[] = [
                'errorLevelEnum' => $messageItem->getErrorLevelEnum()->getValue(),
                'message' => $messageItem->getMessage(),
            ];
        }
        OptionService::updateOption(OptionEnum::MESSAGE_BUCKET(), serialize($messageBucketData));
    }

    /**
     * @return MessageBucket
     */
    public static function loadMessageBucket(): MessageBucket
    {
        $messageBucket = new MessageBucket();
        $messageBucketData = unserialize(OptionService::getOption(OptionEnum::MESSAGE_BUCKET(), 'a:0:{}'), [false]);
        if (!is_array($messageBucketData)) {
            return $messageBucket;
        }
        foreach ($messageBucketData as $messageItem) {
            if (ErrorLevelEnum::isValidValue($messageItem['errorLevelEnum'])) {
                $messageEnum = ErrorLevelEnum::create($messageItem['errorLevelEnum']);
                $messageBucket->addMessageItem($messageEnum, $messageItem['message']);
            }
        }

        return $messageBucket;
    }
}