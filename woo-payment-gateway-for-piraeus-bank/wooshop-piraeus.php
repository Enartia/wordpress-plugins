<?php
/*
  Plugin Name: Piraeus Bank WooCommerce Payment Gateway
  Plugin URI: https://www.papaki.com
  Description: Piraeus Bank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards On your Woocommerce Powered Site.
  Version: 2.0.0
  Author: Papaki
  Author URI: https://www.papaki.com
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  WC tested: 8.5.0
  Text Domain: woo-payment-gateway-for-piraeus-bank
  Domain Path: /languages
*/
/*
Based on original plugin "Piraeus Bank Greece Payment Gateway for WooCommerce" by emspace.gr [https://wordpress.org/plugins/woo-payment-gateway-piraeus-bank-greece/]
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', 'woocommerce_piraeusbank_init', 0);
add_filter('woocommerce_states', 'piraeus_woocommerce_states');

function woocommerce_piraeusbank_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    load_plugin_textdomain('woo-payment-gateway-for-piraeus-bank', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    add_action('before_woocommerce_init', function () {
        global $wpdb;

        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility($wpdb->prefix . 'piraeusbank_transactions', __FILE__, true);
        }
    });

    require_once 'functions.php';
    require_once 'classes/WC_Piraeusbank_Gateway.php';

    add_action('wp', 'piraeusbank_message');
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_piraeusbank_gateway');
    add_filter('plugin_action_links', function ($links, $file) {
        static $this_plugin;

        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {
            $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_Piraeusbank_Gateway">Settings</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }, 10, 2);
}
