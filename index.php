<?php
/**
 * Plugin Name:     Email Fields for WooCommerce
 * Plugin URI:      https://www.penguinet.it/progetti/email-fields-for-woocommerce
 * Description:     Manage Reply To and BCC of the WooCommerce emails
 * Author:          Erika Gili
 * Author URI:      https://www.penguinet.it
 * Text Domain:     email-fields-for-woocommerce
 * Domain Path:     /languages
 * Version:         1.1.3
 */

declare(strict_types=1);

namespace Dottxado\EmailFieldsForWoocommerce;

if (!defined('ABSPATH')) {
    return;
}

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    return;
}
require_once __DIR__ . '/vendor/autoload.php';

GeneralFields::instance();
