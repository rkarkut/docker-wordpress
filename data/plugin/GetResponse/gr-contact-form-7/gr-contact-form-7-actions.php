<?php

defined( 'ABSPATH' ) || exit;

// Update Contact Form 7 settings in admin section.
add_action( 'gr_settings_run', 'update_contact_form_7_settings' );

// Add new contact to campaign.
add_action( 'wpcf7_before_send_mail', 'gr_add_contact_from_contact_form_7', 5, 1 );

/**
 * Update Contact Form 7 settings.
 */
function update_contact_form_7_settings() {

    if ( false === gr()->contactForm7->is_active() ) {
        return false;
    }
    // Contact Form 7 settings.
    if ( null === ( gr_post( 'cf7_registration_on' ) ) ) {
        return false;
    }

    gr_update_option( 'cf7_registration_on', gr_post( 'cf7_registration_on' ) );

    if ( '1' === gr_post( 'cf7_registration_on' ) ) {
        gr_update_option( 'cf7_registration_campaign', gr_post( 'cf7_registration_campaign' ) );
    }

    gr()->add_success_message('Settings saved.');
}

/**
 * Add contact to GetResponse.
 * @return bool
 */
function gr_add_contact_from_contact_form_7() {

    if ( false === gr()->contactForm7->is_enabled() ) {
        return false;
    }

    $name = WPCF7_Submission::get_instance()->get_posted_data('your-name');
    $email = WPCF7_Submission::get_instance()->get_posted_data('your-email');
    $confirmation = reset(WPCF7_Submission::get_instance()->get_posted_data('signup-to-newsletter'));

    if (!empty($confirmation) && !empty($email)) {
        gr()->contactForm7->add_contact($name, $email);
    }
}
