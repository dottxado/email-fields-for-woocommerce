<?php

declare(strict_types=1);

namespace Dottxado\EmailFieldsForWoocommerce;

class GeneralFields
{
    private static $instance;

    const OPTION_REPLY_TO_NAME = 'effw_reply_to_name';

    const OPTION_REPLY_TO_EMAIL = 'effw_reply_to_address';

    const OPTION_BCC_EMAIL = 'effw_bcc_address';

    private function __construct()
    {
        add_filter('woocommerce_email_settings', [$this, 'addReplyToSettings']);
        add_filter('woocommerce_email_settings', [$this, 'addBccSettings']);
        add_filter('woocommerce_email_headers', [$this, 'addReplyToHeader']);
        add_filter('woocommerce_email_headers', [$this, 'addBccHeader']);
    }

    public static function instance(): GeneralFields
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addReplyToSettings(array $settings): array
    {
        $additionalSettings = [
            [
                'title' => __('Email Reply-To options', 'email-fields-for-woocommerce'),
                'type' => 'title',
                'desc' => '',
                'id' => 'effw_reply_to_email_options',
            ],
            [
                'title' => __('"Reply-To" name', 'email-fields-for-woocommerce'),
                'desc' => __(
                    'How the Reply-To name appears in outgoing WooCommerce emails.',
                    'email-fields-for-woocommerce'
                ),
                'id' => 'effw_reply_to_name',
                'type' => 'text',
                'css' => 'min-width:400px;',
                'default' => '',
                'autoload' => false,
                'desc_tip' => true,
            ],
            [
                'title' => __('"Reply-To" address', 'email-fields-for-woocommerce'),
                'desc' => __(
                    'The Reply-To email address in outgoing WooCommerce emails.',
                    'email-fields-for-woocommerce'
                ),
                'id' => 'effw_reply_to_address',
                'type' => 'email',
                'custom_attributes' => [
                    'multiple' => 'multiple',
                ],
                'css' => 'min-width:400px;',
                'default' => '',
                'autoload' => false,
                'desc_tip' => true,
            ],
            [
                'type' => 'sectionend',
                'id' => 'effw_reply_to_email_options',
            ],
        ];

        return array_merge($settings, $additionalSettings);
    }

    public function addBccSettings(array $settings): array
    {
        $additionalSettings = [
            [
                'title' => __('Email Bcc options', 'email-fields-for-woocommerce'),
                'type' => 'title',
                'desc' => '',
                'id' => 'effw_bcc_email_options',
            ],
            [
                'title' => __('"Bcc" address', 'email-fields-for-woocommerce'),
                'desc' => __(
                    'Add a blind carbon copy address in outgoing WooCommerce emails.',
                    'email-fields-for-woocommerce'
                ),
                'id' => 'effw_bcc_address',
                'type' => 'email',
                'custom_attributes' => [
                    'multiple' => 'multiple',
                ],
                'css' => 'min-width:400px;',
                'default' => '',
                'autoload' => false,
                'desc_tip' => true,
            ],
            [
                'type' => 'sectionend',
                'id' => 'effw_bcc_email_options',
            ],
        ];

        return array_merge($settings, $additionalSettings);
    }

    public function addReplyToHeader(string $headers): string
    {
        if (false !== strpos($headers, 'Reply-to')) {
            return $headers;
        }
        $optionName = wp_specialchars_decode(
            get_option(self::OPTION_REPLY_TO_NAME, ''),
            ENT_QUOTES
        );
        $optionEmail = sanitize_email(get_option(self::OPTION_REPLY_TO_EMAIL, ''));
        if (!empty($optionEmail) && !empty($optionName) && is_email($optionEmail)) {
            $headers .= "Reply-to: $optionName <$optionEmail>\r\n";
        }

        return $headers;
    }

    public function addBccHeader(string $headers): string
    {
        if (false !== strpos($headers, 'Bcc')) {
            return $headers;
        }
        $optionEmail = sanitize_email(get_option(self::OPTION_BCC_EMAIL, ''));
        if (!empty($optionEmail) && !empty($optionName) && is_email($optionEmail)) {
            $headers .= "Bcc: $optionEmail\r\n";
        }

        return $headers;
    }
}
