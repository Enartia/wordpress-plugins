<?php
/*
  Plugin Name: Payment Gateway – nexi Alpha Bank for WooCommerce
  Plugin URI: https://www.papaki.com
  Description: Payment Gateway – nexi Alpha Bank for WooCommerce allows you to accept payment through various channels such as American Express, Visa, Mastercard, Maestro, Diners Club cards On your Woocommerce Powered Site.
  Version: 2.0.0
  Author: Papaki
  Author URI: https://www.papaki.com
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  WC tested: 8.5.0
  Text Domain: woo-alpha-bank-payment-gateway
*/

if (!defined('ABSPATH')) {
    exit;
}
add_action('plugins_loaded', 'woocommerce_alphabank_init', 0);

function woocommerce_alphabank_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    load_plugin_textdomain('woo-alpha-bank-payment-gateway', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    require_once 'classes/WC_AlphaBank_Gateway_Base.php';
    require_once 'classes/WC_AlphaBank_Gateway.php';
    require_once 'classes/WC_AlphaBank_Gateway_Masterpass.php';
    require_once 'functions.php';
}
