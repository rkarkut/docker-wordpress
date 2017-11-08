<?php

defined( 'ABSPATH' ) || exit;

// Add BuddyPress custom stylesheets.
add_action( 'wp_head', 'gr_add_buddypress_custom_stylesheet' );

// Update BuddyPress settings in admin section.
add_action( 'gr_settings_run', 'gr_update_buddypress_settings' );

// Add checkbox on registration page.
add_action( 'bp_before_registration_submit_buttons', 'gr_buddypress_add_checkbox_to_registration_form', 5 );

// Add email to waiting list.
add_action( 'bp_core_signup_user', 'gr_buddypress_serve_registration_form', 5, 1 );

// Add new contact to campaign and clear waiting list.
add_action( 'bp_core_activated_user', 'gr_buddypress_add_contact_from_activation_page', 5, 1 );

/**
 * Update BuddyPress settings.
 */
function gr_update_buddypress_settings() {

	if ( false === gr()->buddypress->is_active() ) {
		return false;
	}
	// BuddyPress settings.
	if ( null === ( gr_post( 'bp_registration_on' ) ) ) {
		return false;
	}

	gr_update_option( 'bp_registration_on', gr_post( 'bp_registration_on' ) );

	if ( '1' === gr_post( 'bp_registration_on' ) ) {

		gr_update_option( 'bp_registration_label', gr_post( 'bp_registration_label' ) );
		gr_update_option( 'bp_registration_campaign', gr_post( 'bp_registration_campaign' ) );
		gr_update_option( 'bp_registration_checked', gr_post( 'bp_registration_checked' ) );
	}

	gr()->add_success_message(__('Settings saved', 'Gr_Integration'));
}

/**
 * Add custom css file.
 */
function gr_add_buddypress_custom_stylesheet() {

	if ( false === gr()->buddypress->is_enabled() ) {
		return false;
	}

	echo '<link rel="stylesheet" id="gr-bp-css" href="' . gr()->asset_path . '/css/getresponse-bp-form.css" type="text/css" media="all">';
}

/**
 * Add contact to GetResponse on activation step.
 */
function gr_buddypress_add_contact_from_activation_page() {

	if ( false === gr()->buddypress->is_enabled() ) {
		return false;
	}

	$emails = gr()->buddypress->get_waiting_emails();

	if ( empty( $emails ) ) {
		return false;
	}

	foreach ( $emails as $k => $v ) {

		$user = gr()->db->get_user_details_by_email( $v );

		if ( true === empty( $user ) ) {
			continue;
		}

		// Add contact to campaign.
		if ( gr()->buddypress->add_contact( $user ) ) {

			// Remove email from waitling list.
			gr()->buddypress->remove_user_from_waiting_list( $user );
		}
	}

	// Clear waiting list with expired emails.
	gr()->buddypress->clear_waiting_list();
}

/**
 * Add checkbox to BoddyPress registration page.
 *
 * @return bool
 */
function gr_buddypress_add_checkbox_to_registration_form() {

	if ( false === gr()->buddypress->is_enabled() ) {
		return false;
	}

	gr_load_template( 'page/partials/bp_registration_form.php' );

}

/**
 * Grab BoddyPress registration form.
 *
 * @return bool
 */
function gr_buddypress_serve_registration_form() {

	if ( '1' !== gr_post( 'gr_bp_checkbox' ) ) {
		return false;
	}

	gr()->buddypress->store_email_in_waiting_list( gr_post( 'signup_email' ) );
}
