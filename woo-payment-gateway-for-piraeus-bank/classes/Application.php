<?php

require_once __DIR__ . '/../functions.php';

class Application {
	private $entrypoint_path;
	
	public function __construct( $entrypoint ) {
		add_action( 'plugins_loaded', [ $this, 'init' ], 0 );
		add_filter( 'woocommerce_states', 'piraeus_woocommerce_states' );
		
		$this->entrypoint_path = $entrypoint;

		add_action( 'before_woocommerce_init', [ $this, 'declare_cart_checkout_blocks_compatibility' ] );
		add_action( 'before_woocommerce_init', [ $this, 'declare_transactions' ] );
		add_action( 'woocommerce_blocks_loaded', [ $this, 'woo_register_order_approval_payment_method_type' ] );
	}
	
	public function init() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}
		
		require_once 'WC_Piraeusbank_Gateway.php';
		
		load_plugin_textdomain( 'woo-payment-gateway-for-piraeus-bank', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		//See functions.php; move these?
		add_action( 'wp', 'piraeusbank_message' );
		add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_piraeusbank_gateway' );
		add_filter( 'plugin_action_links', 'piraeusbank_plugin_action_links', 10, 2 );
	}
	
	/**
	 * Custom function to declare compatibility with piraeusbank_transactions feature
	 */
	public function declare_transactions() {
		global $wpdb;
		
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( $wpdb->prefix . 'piraeusbank_transactions', $this->entrypoint_path, true );
		}
	}
	
	/**
	 * Custom function to declare compatibility with cart_checkout_blocks feature
	 */
	public function declare_cart_checkout_blocks_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', $this->entrypoint_path, true );
		}
	}
	
	/**
	 * Custom function to register a payment method type
	 */
	public function woo_register_order_approval_payment_method_type() {
		if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			return;
		}
		
		require_once plugin_dir_path( __FILE__ ) . '/WC_Piraeusbank_Gateway_Checkout_Block.php';
		
		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function ( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				// Register an instance of WC_Phonepe_Blocks
				$payment_method_registry->register( new WC_Piraeusbank_Gateway_Checkout_Block );
			}
		);
	}
}