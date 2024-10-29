<?php
/**
 * Plugin Name:     Email Fields for WooCommerce
 * Description:     Manage Reply To and BCC of the WooCommerce emails
 * Author:          Erika Gili
 * Author URI:      https://github.com/dottxado
 * Text Domain:     email-fields-for-woocommerce
 * Domain Path:     /languages
 * Version:         1.5.0
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

new GeneralFields();
