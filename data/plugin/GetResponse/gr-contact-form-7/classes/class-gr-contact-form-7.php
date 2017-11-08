<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class GR_ContactForm7
 *
 * Contact Form 7 plugin.
 */
class GR_ContactForm7 {

    /**
     * Check, if Contact Form 7 is active.
     */
    public function is_active() {

        $plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

        if ( false === in_array( 'contact-form-7/wp-contact-form-7.php', $plugins ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check, if plugin is active.
     */
    public function is_enabled() {

        if (false === $this->is_active()) {
            return false;
        }

        if ( '1' !== gr_get_option( 'cf7_registration_on' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Add contact to campaign.
     *
     * @param string $name
     * @param string $email
     *
     * @return bool
     */
    public function add_contact( $name, $email ) {

        if ( null === gr_get_option( 'api_key' ) ) {
            return false;
        }

        gr()->page->add_contact(
            gr_get_option( 'cf7_registration_campaign' ),
            $name,
            $email
        );

        return true;
    }
}

gr()->contactForm7 = new GR_ContactForm7();
