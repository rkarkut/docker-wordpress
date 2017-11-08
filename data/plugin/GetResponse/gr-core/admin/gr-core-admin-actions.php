<?php

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'gr_admin_settings_page' );
add_action( 'admin_init', 'gr_admin_init' );
/**
 * Register admin init hook.
 */
function gr_admin_init() {

	register_setting( 'gr-integration', 'gr-options-name' );
}

/**
 * Register admin settings page.
 */
function gr_admin_settings_page() {

	$position = null;
	$icon_url = gr()->asset_path . '/img/menu_icon.png';

	add_menu_page(
		__( gr()->plugin_name, 'Gr_Integration' ),
		__( gr()->plugin_name, 'Gr_Integration' ),
		'manage_options',
		'gr-integration',
		'gr_create_status_page',
		$icon_url,
		$position
	);

	add_submenu_page(
		'gr-integration',        // parent slug, same as above menu slug
		'',        // empty page title
		'',        // empty menu title
		'manage_options',        // same capability as above
		'gr-integration',        // same menu slug as parent slug
		'gr_create_status_page'      // same function as above
	);

	remove_submenu_page( 'gr-integration', 'gr-integration' );

	// Status page.
	add_submenu_page(
		'gr-integration',
		__('Status & Configuration', 'Gr_Integration'),
		__('Status & Configuration', 'Gr_Integration'),
		'manage_options',
		'gr-integration-status',
		'gr_create_status_page'
	);

	if ( null !== gr_get_option( 'api_key' ) && gr()->check_requirements() ) {

		// Add Common Settings page.
		add_submenu_page(
			'gr-integration',
			__('Subscription Settings', 'Gr_Integration'),
			__('Subscription Settings', 'Gr_Integration'),
			'manage_options',
			'gr-integration-subscription-settings',
			'gr_create_subscription_settings_page'
		);


		// Add Web Form page.
		add_submenu_page(
			'gr-integration',
			__('WebForm Shortcode', 'Gr_Integration'),
			__('WebForm Shortcode', 'Gr_Integration'),
			'manage_options',
			'gr-integration-web-form',
			'gr_create_webform_page'
		);

		if ( gr()->buddypress->is_active() ) {

			// Add BuddyPress page.
			add_submenu_page(
				'gr-integration',
				__('BuddyPress Integration', 'Gr_Integration'),
				__('BuddyPress Integration', 'Gr_Integration'),
				'manage_options',
				'gr-integration-buddypress',
				'gr_create_buddypress_page'
			);
		}

		if ( gr()->woocommerce->is_active() ) {

			// Add WooCommerce page.
			add_submenu_page(
				'gr-integration',
				__('Integracja WooCommerce', 'Gr_Integration'),
				__('Integracja WooCommerce', 'Gr_Integration'),
				'manage_options',
				'gr-integration-woocommerce',
				'gr_create_woocommerce_page'
			);
		}

        if ( gr()->contactForm7->is_active() ) {

            // Add BuddyPress page.
            add_submenu_page(
                'gr-integration',
                'Contact Form 7 Integration',
                'Contact Form 7 Integration',
                'manage_options',
                'gr-integration-contact-form-7',
                'gr_create_contact_form_7_page'
            );
        }
	}

	// Help page.
	add_submenu_page(
		'gr-integration',
		__('Help', 'Gr_Integration'),
		__('Help', 'Gr_Integration'),
		'manage_options',
		'gr-integration-help',
		'gr_create_help_page'
	);

	add_submenu_page(
		'gr-integration',        // parent slug, same as above menu slug
		'',        // empty page title
		'',        // empty menu title
		'manage_options',        // same capability as above
		'gr-integration-error',        // same menu slug as parent slug
		'gr_create_error_page'      // same function as above
	);

// Enqueue CSS.
	wp_enqueue_style( 'GrStyle' );
	wp_enqueue_style( 'GrCustomsStyle' );

// Enqueue JS.
	wp_enqueue_script( 'GrCustomsJs' );

// Detect adblock.
	wp_register_script( 'GrAdsJs', gr()->asset_path . '/js/ads.js' );
	wp_enqueue_script( 'GrAdsJs' );

// run main settings action.
	do_action( 'gr_settings_run' );
}


function gr_create_error_page() {
	gr_load_template( 'admin/settings/error-page.php' );
}

/**
 * Load WooCommerce page.
 */
function gr_create_woocommerce_page() {
	gr_load_template( 'admin/settings/woocommerce.php' );
}

/**
 * Load BuddyPress page.
 */
function gr_create_buddypress_page() {
	gr_load_template( 'admin/settings/buddypress.php' );
}

/**
 * Load ContactForm7 page.
 */
function gr_create_contact_form_7_page() {
	gr_load_template( 'admin/settings/contact_form_7.php' );
}

/**
 * Load help page.
 */
function gr_create_help_page() {
	gr_load_template( 'admin/settings/help.php' );
}

/**
 * Load Common Settings template.
 */
function gr_create_subscription_settings_page() {

	if ( false === gr()->check_requirements() ) {
		gr_load_template( 'admin/settings/error-page.php' );

		return;
	}

	gr_load_template( 'admin/settings/subscription_settings.php' );
}

/**
 * Load Web Form template.
 */
function gr_create_webform_page() {

	if ( false === gr()->check_requirements() ) {
		gr_load_template( 'admin/settings/error-page.php' );

		return;
	}

	gr_load_template( 'admin/settings/webform.php' );
}

/**
 * Create settings page in admin section.
 */
function gr_create_settings_page() {

	if ( false === gr()->check_requirements() ) {
		gr_load_template( 'admin/settings/error-page.php' );

		return;
	}

	gr_load_template( 'admin/settings/common_api_key.php' );
}

/**
 * Load status page.
 */
function gr_create_status_page() {

    if ('disconnect' === gr_get_value('action')) {
        gr_disconnect_from_gr();
        return;
    }

	if ( false === gr()->check_requirements() ) {
		gr_load_template( 'admin/settings/error-page.php' );

		return;
	}

	if ( null !== gr_get_option( 'api_key' ) ) {
		gr_load_template( 'admin/settings/status.php' );
	} else {
		gr_load_template( 'admin/settings/common_no_api_key.php' );
	}
}

/**
 * Disconnect user from API.
 */
function gr_disconnect_from_gr() {

    gr_delete_option( 'api_key' );
    gr_delete_option( 'api_domain' );
    gr_delete_option( 'api_url' );
    gr_delete_option( 'getresponse_360_account' );

    gr()->add_success_message( __('You have been disconnected', 'Gr_Integration') );
    gr_load_template( 'admin/settings/common_no_api_key.php' );
}

