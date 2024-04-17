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

/**
 * @return void
 */
function alphabank_message()
{
    $order_id = absint(get_query_var('order-received'));
    $order = new WC_Order($order_id);
    if (method_exists($order, 'get_payment_method')) {
        $payment_method = $order->get_payment_method();
    } else {
        $payment_method = $order->payment_method;
    }
    if ('alphabank_gateway' === $payment_method && is_order_received_page()) {
        $alphabank_message = $order->get_meta('_alphabank_message');

        if (!empty($alphabank_message)) {
            $message = $alphabank_message['message'];
            $message_type = $alphabank_message['message_type'];
            if (method_exists($order, 'delete_meta_data')) {
                $order->delete_meta_data('_alphabank_message');
                $order->save_meta_data();
            } else {
                delete_post_meta($order_id, '_alphabank_message');
            }
            wc_add_notice($message, $message_type);
        }
    }
}

add_action('wp', 'alphabank_message');

/**
 * @param array $methods
 * @return array
 */
function woocommerce_add_alphabank_gateway($methods)
{
    $methods[] = 'WC_AlphaBank_Gateway';
    $methods[] = 'WC_AlphaBank_Gateway_Masterpass';
    return $methods;
}

add_filter('woocommerce_payment_gateways', 'woocommerce_add_alphabank_gateway');
add_filter('plugin_action_links', 'alphabank_plugin_action_links', 10, 2);

/**
 * @param $links
 * @param $file
 * @return mixed
 */
function alphabank_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file === $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_alphabank_Gateway">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
