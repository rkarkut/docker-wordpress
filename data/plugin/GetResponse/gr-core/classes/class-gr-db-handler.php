<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class GR_Db_Queries
 *
 * Database queries.
 */
class GR_Db_Handler {

	/**
	 * Return user details by email address
	 *
	 * @param $email
	 *
	 * @return bool|mixed
	 */
	public function get_user_details_by_email( $email ) {
		if ( empty( $email ) ) {
			return false;
		}

		global $wpdb;

		$sql = "
			SELECT
			users.user_email,
			users.display_name,
			users.user_registered,
			(SELECT usermeta.meta_value FROM $wpdb->usermeta usermeta
			WHERE usermeta.meta_key = 'activation_key' and usermeta.user_id = users.ID) as activation_key
			FROM $wpdb->users users
			WHERE
			users.user_email = '" . $email . "'
			";

		return $wpdb->get_row( $sql );
	}

	/**
	 * Return GetResponse plugin details
	 *
	 * @return bool|mixed
	 */
	public function get_gr_plugin_details() {

		global $wpdb;
		$sql = "
			SELECT *
			FROM $wpdb->options options
			WHERE options.`option_name` LIKE 'GrIntegrationOptions%'
			ORDER BY options.`option_name` DESC;
			";

		return $wpdb->get_results( $sql );
	}

}

gr()->db = new GR_Db_Handler();
