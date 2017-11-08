<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class BuddyPress.
 *
 * BuddyPress plugin.
 *
 */
class GR_BuddyPress {

	/**
	 * BuddyPress Max days limit.
	 *
	 * If user do not confirm activation
	 * link until this number of days
	 * will be removed from queue.
	 *
	 * @var string
	 */
	public $max_bp_unconfirmed_days = '30';

	/**
	 * Check, if plugin is active.
	 */
	public function is_active() {

		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		if ( false === in_array( 'buddypress/bp-loader.php', $plugins ) ) {
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

		if ( '1' !== gr_get_option( 'bp_registration_on' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add contact to campaign.
	 *
	 * * @param $user
	 *
	 * @return bool
	 */
	public function add_contact( $user ) {

		if ( null !== $user->activation_key ) {
			return false;
		}

		if ( null === gr_get_option( 'api_key' ) ) {
			return false;
		}

		gr()->page->add_contact(
			gr_get_option( 'bp_registration_campaign' ),
			$user->display_name,
			$user->user_email,
			0
		);

		return true;
	}

	public function store_email_in_waiting_list( $email ) {

		if ( empty( $email ) ) {
			return false;
		}

		$emails = $this->get_waiting_emails();

		// If emails not found - add only this one.
		if ( empty( $emails ) ) {
			$new_emails = array( $email );
		} else {
			$new_emails = array_merge( $emails, array( $email ) );
		}

		gr_update_option( 'bp_registered_emails', serialize( $new_emails ) );
	}

	/**
	 * Get emails from waiting list.
	 *
	 * @return bool
	 */
	public function get_waiting_emails() {

		$emails = gr_get_option( 'bp_registered_emails' );

		if ( empty( $emails ) ) {
			return array();
		}

		return unserialize( $emails );
	}

	/**
	 * Remove contact from waiting list if required.
	 *
	 * * @param $user
	 *
	 */
	public function remove_user_from_waiting_list( $user ) {

		$emails = $this->get_waiting_emails();

		if ( empty( $emails ) ) {
			return;
		}

		foreach ( $emails as $k => $v ) {

			if ( $user->user_email == $v ) {
				unset( $emails[ $k ] );
			}
		}

		gr_update_option( 'bp_registered_emails', serialize( $emails ) );
	}

	/**
	 * Clear waiting list.
	 *
	 */
	public function clear_waiting_list() {

		$emails = $this->get_waiting_emails();

		if ( empty( $emails ) ) {
			return;
		}

		foreach ( $emails as $k => $v ) {

			$user = gr()->db->get_user_details_by_email( $v );

			$diff = gr()->page->get_date_diff( date( 'Y-m-d H:i:s' ), $user->user_registered );

			if ( $diff >= $this->max_bp_unconfirmed_days ) {
				unset( $k );
			}
		}

		gr_update_option( 'bp_registered_emails', serialize( $emails ) );
	}
}

gr()->buddypress = new GR_BuddyPress();
