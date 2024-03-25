<?php
/*
Plugin Name: Eurobank WooCommerce Payment Gateway
Plugin URI: https://www.papaki.com
Description: Eurobank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners and Visa cards On your Woocommerce Powered Site.
Version: 1.9.0
Author: Papaki
Author URI: https://www.papaki.com
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
WC tested: 8.5.0
Text Domain: woo-payment-gateway-for-eurobank
Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', 'woocommerce_eurobank_init', 0);

function woocommerce_eurobank_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    require_once 'classes/WC_Eurobank_Gateway.php';
    require_once 'functions.php';

    load_plugin_textdomain(WC_Eurobank_Gateway::PLUGIN_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
