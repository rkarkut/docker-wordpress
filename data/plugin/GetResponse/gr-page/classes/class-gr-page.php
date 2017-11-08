<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class GR_Page
 *
 * GetResponse front page integrations.
 *
 */
class GR_Page {

    const CUSTOM_TYPE = 'wordpress';

	/**
	 *
	 * All custom fields.
	 *
	 * @var
	 */
	private $all_custom_fields;

	/**
	 * Add new contact to campaign.
	 *
	 * @param string $campaign campaign name.
	 * @param string $name client name.
	 * @param string $email client email.
	 * @param int $cycle_day cycle day.
	 * @param array $user_customs custom fields.
	 *
	 * @return mixed
	 */
	public function add_contact( $campaign, $name, $email, $cycle_day = 0, $user_customs = array() ) {

		if (empty($name)) {
			$name = $this->get_contact_name($email);
		}

        $user_customs['origin'] = self::CUSTOM_TYPE;

		$params = array(
			'name'       => $name,
			'email'      => $email,
			'dayOfCycle' => $cycle_day,
			'campaign'   => array( 'campaignId' => $campaign ),
			'ipAddress'  => $_SERVER['REMOTE_ADDR'],
		);

		$this->all_custom_fields = $this->get_custom_fields();

		$results = array();

		try {
            $results = (array)gr()->api->get_contacts([
                'query' => [
                    'email' => $email,
                    'campaignId' => $campaign
                ]
            ]);
        } catch (Unauthorized_Request_Exception $e) {
            gr()->disconnect_integration();
        }

		$contact = array_pop( $results );

        try {

            // If contact already exists in gr account.
            if ( ! empty( $contact ) && isset( $contact->contactId ) ) {

                $results = gr()->api->get_contact($contact->contactId);

                if ( ! empty( $results->customFieldValues ) ) {

                    $params['customFieldValues'] = $this->merge_user_customs( $results->customFieldValues, $user_customs );
                } else {
                    $params['customFieldValues'] = $this->set_customs( $user_customs );
                }

                return gr()->api->update_contact( $contact->contactId, $params );
            } else {
                $params['customFieldValues'] = $this->set_customs( $user_customs );

                return gr()->api->add_contact( $params );
            }

        } catch (Unauthorized_Request_Exception $e) {
            gr()->disconnect_integration();
        }
	}

	/**
	 * Get all custom fields.
	 *
	 * @return array
	 */
	public function get_custom_fields() {

		$all_customs = array();

		try {
            $results = gr()->api->get_custom_fields();
        } catch (Unauthorized_Request_Exception $e) {
            gr()->disconnect_integration();
        }

		if ( empty( $results ) ) {
			return $all_customs;
		}

		foreach ( $results as $ac ) {
			if ( isset( $ac->name ) && isset( $ac->customFieldId ) ) {
				$all_customs[ $ac->name ] = $ac->customFieldId;
			}
		}

		return $all_customs;
	}

	/**
	 * Set customs.
	 *
	 * * @param $user_customs
	 *
	 * @return array
	 */
	public function set_customs( $user_customs ) {

		$custom_fields = array();

		if ( empty( $user_customs ) ) {
			return $custom_fields;
		}

		foreach ( $user_customs as $name => $value ) {

			// If custom field is already created on gr account set new value.
			if ( in_array( $name, array_keys( $this->all_custom_fields ) ) ) {
				$custom_fields[] = array(
					'customFieldId' => $this->all_custom_fields[ $name ],
					'value'         => array( $value ),
				);
			} // create new custom field
			else {

			    try {
                    $custom = gr()->api->add_custom_field([
                        'name' => $name,
                        'type' => "text",
                        'hidden' => "false",
                        'values' => [$value],
                    ]);
                } catch (Unauthorized_Request_Exception $e) {
			        gr()->disconnect_integration();
                }

				if ( ! empty( $custom ) && ! empty( $custom->customFieldId ) ) {
					$custom_fields[] = array(
						'customFieldId' => $custom->customFieldId,
						'value'         => array( $value )
					);
				}
			}
		}

		return $custom_fields;
	}

	/**
	 * Merge account custom fields.
	 *
	 * @param array $results results.
	 * @param array $user_customs user customs.
	 *
	 * @return array
	 */
	public function merge_user_customs( $results, $user_customs ) {
		$custom_fields = array();

		if ( is_array( $results ) ) {
			foreach ( $results as $customs ) {
				$value = $customs->value;
				if ( in_array( $customs->name, array_keys( $user_customs ) ) ) {
					$value = array( $user_customs[ $customs->name ] );
					unset( $user_customs[ $customs->name ] );
				}

				$custom_fields[] = array(
					'customFieldId' => $customs->customFieldId,
					'value'         => $value,
				);
			}
		}

		return array_merge( $custom_fields, $this->set_customs( $user_customs ) );
	}

	/**
	 * Return different between current date and registered date in days
	 *
	 * @param string $now current date.
	 * @param string $user_registered_date user date.
	 *
	 * @return bool
	 */
	public function get_date_diff( $now, $user_registered_date ) {
		$now       = strtotime( $now );
		$user_date = strtotime( $user_registered_date );
		$diff      = $now - $user_date;

		return floor( $diff / 3600 / 24 );
	}

	private function get_contact_name($email)
	{
		preg_match('/[\w]+/i', $email, $result);
		$name = isset($result[0]) ? $result[0] : null;

		return empty($name) ? 'Friend' : $name;
	}
}

gr()->page = new GR_Page();
