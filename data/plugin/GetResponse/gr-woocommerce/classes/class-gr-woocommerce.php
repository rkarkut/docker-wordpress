<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class WooCommerce
 *
 * WooCommerce plugin.
 *
 */
class GR_WooCommerce {

	/**
	 *
	 * Billing fields.
	 *
	 * @var array
	 */
	public $biling_fields = array(
		'firstname' => array( 'value' => 'billing_first_name', 'name' => 'firstname', 'default' => 'yes' ),
		'lastname'  => array( 'value' => 'billing_last_name', 'name' => 'lastname', 'default' => 'yes' ),
		'email'     => array( 'value' => 'billing_email', 'name' => 'email', 'default' => 'yes' ),
		'address'   => array( 'value' => 'billing_address_1', 'name' => 'address', 'default' => 'no' ),
		'city'      => array( 'value' => 'billing_city', 'name' => 'city', 'default' => 'no' ),
		'state'     => array( 'value' => 'billing_state', 'name' => 'state', 'default' => 'no' ),
		'telephone'     => array( 'value' => 'billing_phone', 'name' => 'telephone', 'default' => 'no' ),
		'country'   => array( 'value' => 'billing_country', 'name' => 'country', 'default' => 'no' ),
		'company'   => array( 'value' => 'billing_company', 'name' => 'company', 'default' => 'no' ),
		'postcode'  => array( 'value' => 'billing_postcode', 'name' => 'postcode', 'default' => 'no' )
	);

	/**
	 * Check, if plugin is active.
	 */
	public function is_active() {

		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		if ( in_array( 'woocommerce/woocommerce.php', $plugins ) ) {
			return true;
		}

		return false;
	}
}

gr()->woocommerce = new GR_WooCommerce();
