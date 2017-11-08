<?php

defined( 'ABSPATH' ) || exit;

add_action( 'comment_form', 'gr_checkbox_add_to_comments_form' );
add_action( 'comment_post', 'gr_grab_email_from_comment' );
add_action( 'register_form', 'gr_add_checkbox_to_registration_form' );
add_action( 'user_register', 'gr_grab_email_from_registration_form' );

/**
 * Add checkbox to new comment form.
 */
function gr_checkbox_add_to_comments_form() {

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	if ( '1' !== gr_get_option( 'comment_on' ) ) {
		return false;
	}

	gr_load_template( 'page/partials/comment.php' );
}

/**
 * Grab email from comment.
 */
function gr_grab_email_from_comment() {

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	if ( '1' !== gr_post( 'gr_comment_checkbox' ) ) {
		return false;
	}

    if ( true === is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $name = $current_user->user_firstname . ' ' . $current_user->user_lastname;

        if (trime($name) > 1) {
            gr()->page->add_contact( gr_get_option( 'comment_campaign' ), $name, $current_user->user_email );
        }
    } else {
        if ( null === gr_post( 'email' ) || '' === gr_post( 'email' ) ) {
            return false;
        }

        if ( null === gr_post( 'author' ) || '' === gr_post( 'author' ) ) {
            return false;
        }

        gr()->page->add_contact( gr_get_option( 'comment_campaign' ), gr_post( 'author' ), gr_post( 'email' ) );
    }
}

/**
 * Add checkbox to registration form.
 *
 * @return bool
 */
function gr_add_checkbox_to_registration_form() {

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	if ( '1' !== gr_get_option( 'registration_on' ) ) {
		return false;
	}

	if ( true === is_user_logged_in() ) {
		return false;
	}

	gr_load_template( 'page/partials/registration_form.php' );
}

/**
 * Grab email from registration form.
 */
function gr_grab_email_from_registration_form() {

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	if ( '1' !== gr_post( 'gr_registration_checkbox' ) ) {
		return false;
	}

    $email = $name = null;

	if ( false === empty(gr_post( 'user_email' )) ) {
	    $email = gr_post( 'user_email');
	}

	if ( false === empty(gr_post( 'user_login' )) ) {
		$name = gr_post('user_login');
	}

    if ( false === empty(gr_post( 'email' )) ) {
        $email = gr_post( 'email');
    }

    if ( false === empty(gr_post('username')) ) {
        $name = gr_post('username');
    }

    if ( false === empty($email) ) {
        gr()->page->add_contact(gr_get_option('registration_campaign'), $name, $email);
    }
}