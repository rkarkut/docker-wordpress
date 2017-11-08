<?php

defined( 'ABSPATH' ) || exit;

add_action( 'woocommerce_after_checkout_billing_form', 'gr_add_checkbox_to_checkout_page', 5 );
add_action( 'woocommerce_ppe_checkout_order_review', 'gr_add_checkbox_to_checkout_page', 5 );
add_action( 'woocommerce_checkout_order_processed', 'gr_grab_email_from_checkout_page', 5, 2 );
add_action( 'woocommerce_ppe_do_payaction', 'gr_paypal_grab_email_from_checkout_page', 5, 1 );
add_action( 'gr_settings_run', 'gr_update_woocommerce_settings' );
add_action('woocommerce_register_form', 'gr_add_checkbox_to_registration_form', 5, 1);
add_action( 'woocommerce_customer_save_address', 'gr_grab_email_from_update_address_details_page' );
add_action( 'woocommerce_save_account_details', 'gr_grab_email_from_edit_account' );

/**
 * Update WooCommerce settings.
 *
 * @return bool
 */
function gr_update_woocommerce_settings() {

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

	if ( false === gr()->woocommerce->is_active() ) {
		return false;
	}

	if ( null === gr_post( 'checkout_on' ) ) {
		return false;
	}

	gr_update_option( 'checkout_on', gr_post( 'checkout_on' ) );

	if ( '1' === gr_post( 'checkout_on' ) ) {

		gr_update_option( 'checkout_label', gr_post( 'checkout_label' ) );
		gr_update_option( 'checkout_campaign', gr_post( 'checkout_campaign' ) );
		gr_update_option( 'checkout_checked', gr_post( 'checkout_checked' ) );
		gr_update_option( 'sync_order_data', gr_post( 'sync_order_data' ) );
	}

	if ( null === gr_post( 'custom_field' ) ) {

		foreach ( array_keys( gr()->woocommerce->biling_fields ) as $value ) {
			gr_delete_option( $value );
		}

		return true;
	}

	$custom_field = gr_post( 'custom_field' );

	// Sync order data - custom fields.
	foreach ( gr()->woocommerce->biling_fields as $value => $bf ) {

		if ( in_array( $value, array_keys( $custom_field ) ) == true && preg_match( '/^[_a-zA-Z0-9]{2,32}$/m',
				stripslashes( $custom_field[ $value ] ) ) == true
		) {
			gr_update_option( $value, $custom_field[ $value ] );
		} else {
			gr_delete_option( $value );
		}
	}

	gr()->add_success_message(__('Settings saved', 'Gr_Integration'));
}

/**
 * @return bool
 */
function gr_grab_email_from_edit_account() {

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

    if ( false === gr()->woocommerce->is_active() ) {
        return false;
    }

    if ( null === gr_get_option( 'api_key' ) ) {
        return false;
    }

    $name = gr_post( 'account_first_name' ) . ' ' . gr_post( 'account_last_name' );
    $email      = gr_post( 'account_email' );

    gr()->page->add_contact(
        gr_get_option( 'registration_campaign' ),
        $name,
        $email,
        0
    );
}

/**
 * @return bool
 */
function gr_grab_email_from_update_address_details_page() {

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

    if ( false === gr()->woocommerce->is_active() ) {
        return false;
    }

    if ( null === gr_get_option( 'api_key' ) ) {
        return false;
    }

    $customs = array();

    $name = gr_post( 'billing_first_name' ) . ' ' . gr_post( 'billing_last_name' );

    if ( '1' === gr_get_option( 'sync_order_data' ) ) {
        foreach ( gr()->woocommerce->biling_fields as $custom_name => $custom_field ) {

            $custom = gr_get_option( $custom_name );

            if ( $custom && null !== gr_post( $custom_field['value'] ) ) {
                $customs[ $custom ] = gr_post( $custom_field['value'] );
            }
        }
    }

    gr()->page->add_contact(
        gr_get_option( 'registration_campaign' ),
        $name,
        gr_post( 'billing_email' ),
        0,
        $customs
    );
}

/**
 * @return bool
 */
function gr_grab_email_from_checkout_page() {

    if ( '1' !== gr_post( 'gr_checkout_checkbox' ) ) {
        return false;
    }

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

	if ( false === gr()->woocommerce->is_active() ) {
		return false;
	}

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	$customs = array();

	$name     = gr_post( 'billing_first_name' ) . ' ' . gr_post( 'billing_last_name' );

	if ( '1' === gr_get_option( 'sync_order_data' ) ) {
		foreach ( gr()->woocommerce->biling_fields as $custom_name => $custom_field ) {
			$custom = gr_get_option( $custom_name );

			if ( $custom && null !== gr_post( $custom_field['value'] ) ) {
				$customs[ $custom ] = gr_post( $custom_field['value'] );
			}
		}
	}

	gr()->page->add_contact( gr_get_option( 'checkout_campaign' ), $name, gr_post( 'billing_email' ), 0, $customs );
}

/**
 * Grab email from checkout form - paypal express
 */
function gr_paypal_grab_email_from_checkout_page() {

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

	if ( '1' !== gr_post( 'gr_checkout_checkbox' ) ) {
		return false;
	}

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	gr()->page->add_contact( gr_get_option( 'checkout_campaign' ), 'Friend', gr_post( 'billing_email' ) );
}

/**
 * Add Checkbox to checkout form
 */
function gr_add_checkbox_to_checkout_page() {

    if (true === gr()->grwp_plugin_is_active()) {
        return false;
    }

	if ( false === gr()->woocommerce->is_active() ) {
		return false;
	}

	if ( '1' !== gr_get_option( 'checkout_on' ) ) {
		return false;
	}

	gr_load_template( 'page/woocommerce/checkbox_checkout.php' );
}
