<?php
/*
Plugin Name: Eurobank WooCommerce Payment Gateway
Plugin URI: https://www.papaki.com
Description: Eurobank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners and Visa cards On your Woocommerce Powered Site.
Version: 2.0.0
Author: Papaki
Author URI: https://www.papaki.com
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
WC tested: 8.5.0
Text Domain: woo-payment-gateway-for-eurobank
Domain Path: /languages
 */

function eurobank_message()
{
    $order_id = absint(get_query_var('order-received'));
    $order = new WC_Order($order_id);
    if (method_exists($order, 'get_payment_method')) {
        $payment_method = $order->get_payment_method();
    } else {
        $payment_method = $order->payment_method;
    }
    if (is_order_received_page() && ('eurobank_gateway' == $payment_method)) {
        if (method_exists($order, 'get_meta')) {
            $eurobank_message = $order->get_meta('_eurobank_message', true);
        } else {
            $eurobank_message = get_post_meta($order_id, '_eurobank_message');
        }

        if (!empty($eurobank_message)) {
            $message = $eurobank_message['message'];
            $message_type = $eurobank_message['message_type'];
            if (method_exists($order, 'delete_meta_data')) {
                $order->delete_meta_data('_eurobank_message');
                $order->save_meta_data();
            } else {
                delete_post_meta($order_id, '_eurobank_message');
            }
            wc_add_notice($message, $message_type);
        }
    }
}

add_action('wp', 'eurobank_message');

/**
 * Add Eurobank Gateway to WC
 * */
function woocommerce_add_eurobank_gateway($methods)
{
    $methods[] = 'WC_Eurobank_Gateway';
    return $methods;
}

add_filter('woocommerce_payment_gateways', 'woocommerce_add_eurobank_gateway');

add_filter('plugin_action_links', 'eurobank_plugin_action_links', 10, 2);

function eurobank_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_Eurobank_Gateway">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;

}