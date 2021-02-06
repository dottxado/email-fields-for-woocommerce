<?php

declare(strict_types=1);

use Dottxado\EmailFieldsForWoocommerce\GeneralFields;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

if (!file_exists(__DIR__.'/vendor/autoload.php')) {
    return;
}
require_once __DIR__.'/vendor/autoload.php';

delete_option(GeneralFields::OPTION_BCC_EMAIL);
delete_option(GeneralFields::OPTION_REPLY_TO_EMAIL);
delete_option(GeneralFields::OPTION_REPLY_TO_NAME);
