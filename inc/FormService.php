<?php

namespace freizeitkalender\eu;

class FormService
{

    /**
     * Joah, speichern hinzufügen
     */
    public static function init(): void
    {
        add_action('admin_post', [__CLASS__, 'saveForm']);
    }

    /**
     * Redirect to the page from which we came (which should always be the
     * admin page. If the referred isn't set, then we redirect the user to
     * the login page.
     *
     * @access private
     * @param MessageBucket $messageBucket
     */
    private static function redirect(MessageBucket $messageBucket): void
    {
        $httpReferer = filter_input(INPUT_POST, FormEnum::_WP_HTTP_REFERER()->getValue(), FILTER_SANITIZE_STRING);

        // Sanitize the value of the $_POST collection for the Coding Standards.
        $redirectUrl = sanitize_text_field(
            wp_unslash($httpReferer) // Input var okay.
        );

        if ($redirectUrl === '') {
            $redirectUrl = wp_login_url();
        }

        // Nachrichten speichern
        MessageService::saveMessageBucket($messageBucket);

        // Finally, redirect back to the admin page.
        wp_safe_redirect(urldecode($redirectUrl));
        exit;
    }

    /**
     * Input Kontrollieren
     * @param FormEnum $formEnum
     * @return string
     */
    private static function sanitizePost(FormEnum $formEnum): string
    {
        $postValue = filter_input(INPUT_POST, $formEnum->getValue(), FILTER_SANITIZE_STRING);
        if (!is_string($postValue)) {
            return '';
        }

        return sanitize_text_field($postValue);
    }

    /**
     * Save Input
     */
    public static function saveForm(): void
    {
        $messageBucket = new MessageBucket();
        // First, validate the nonce and verify the user as permission to save.
        if (!current_user_can('manage_options') || !self::hasValidNonce()) {
            $messageBucket->addMessageItem(ErrorLevelEnum::ERROR(), 'Keine Berechtigung');
            self::redirect($messageBucket);
        }
        // If the above are valid, sanitize and save the option.
        $value = self::sanitizePost(FormEnum::FILTER_ID());
        $filterIdValidator = new FilterIdValidator($value);
        if ($filterIdValidator->isValid()) {
            $filterIdValidator->getMessageBucket()->addMessageItem(ErrorLevelEnum::SUCCESS(), 'Filter ID "' . $value .'" erfolgreich gespeichert.');
            CalendarService::saveFilterId($filterIdValidator->getFilterId());
        } else {
            $filterIdValidator->getMessageBucket()->addMessageItem(ErrorLevelEnum::ERROR(), 'Filter ID "' . $value .'" nicht gespeichert.');
        }
        $messageBucket->appendMessageList($filterIdValidator->getMessageBucket()->getMessageList());

        $value = self::sanitizePost(FormEnum::LIMIT());
        $limitValidator = new LimitValidator($value);
        if ($limitValidator->isValid()) {
            $limitValidator->getMessageBucket()->addMessageItem(ErrorLevelEnum::SUCCESS(), 'Limit "' . $value .'" erfolgreich gespeichert.');
            CalendarService::saveLimit($limitValidator->getLimit());
        } else {
            $limitValidator->getMessageBucket()->addMessageItem(ErrorLevelEnum::ERROR(), 'Limit "' . $value .'" nicht gespeichert.');
        }
        $messageBucket->appendMessageList($limitValidator->getMessageBucket()->getMessageList());
        self::redirect($messageBucket);
    }


    /**
     * @return string
     */
    private static function getNonceAction(): string
    {
        return md5('NonceIstKeinNonsenseSondernDurchausWichtig');
    }

    /**
     * @return bool
     */
    public static function hasValidNonce(): bool
    {
        $nonceValue = filter_input(INPUT_POST, FormEnum::NONCE_FIELD()->getValue(), FILTER_SANITIZE_STRING);
        // If the field isn't even in the $_POST, then it's invalid.
        if (!is_string($nonceValue)) { // Input var okay.
            return false;
        }

        $field = wp_unslash($nonceValue);

        return wp_verify_nonce($field, self::getNonceAction());
    }

    /**
     * @return string
     */
    public static function renderWpHiddenFields(): string
    {
        return wp_nonce_field(self::getNonceAction(), FormEnum::NONCE_FIELD()->getValue(), true, false);
    }

    /**
     * @param FormEnum $formEnum
     * @param string $valueString
     * @param string $label
     * @return string
     */
    private static function renderInputText(FormEnum $formEnum, string $valueString, string $label): string
    {
        return '<p><label>' . esc_html($label) . '<br/><input type="text" name="' . esc_html($formEnum->getValue()) . '" value="' . esc_html($valueString) . '"/></label></p>';
    }

    /**
     * @param string $label
     * @return string
     */
    public static function renderFilterIdInput(string $label): string
    {
        $filterIdValue = (string)CalendarService::loadFilterId();
        return self::renderInputText(FormEnum::FILTER_ID(), $filterIdValue, $label);
    }

    /**
     * @param string $label
     * @return string
     */
    public static function renderLimitInput(string $label): string
    {
        $limitValue = (string)CalendarService::loadLimit();
        return self::renderInputText(FormEnum::LIMIT(), $limitValue, $label);
    }

}
