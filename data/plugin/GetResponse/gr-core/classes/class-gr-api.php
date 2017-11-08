<?php

/**
 * GetResponse API.
 */
class GR_Api {

	/**
	 *
	 * Invalid API Key code.
	 *
	 * @var int
	 */
	public $invalid_apikey_code = 1014;

	/**
	 *
	 * API code on success.
	 *
	 * @var int
	 */
	public $success_api_code = 200;

	/**
	 *
	 * API url for 360.com.
	 *
	 * @var string
	 */
	public $api_url_360_com = 'https://api3.getresponse360.com/v3';

	/**
	 *
	 * API url for 360.pl.
	 *
	 * @var string
	 */
	public $api_url_360_pl = 'https://api3.getresponse360.pl/v3';

	/**
	 *
	 * API url.
	 *
	 * @var null|string
	 */
	public $api_url = 'https://api.getresponse.com/v3';

	/**
	 *
	 * Domain for GetResponse 360.
	 *
	 * @var null
	 */
	public $domain = null;

	/**
	 *
	 * Timeout.
	 *
	 * @var int
	 */
	public $timeout = 8;

	/**
	 *
	 * Https status code.
	 *
	 * @var int
	 */
	public $http_status;

	/**
	 *
	 * Ping result object.
	 *
	 * @var object
	 */
	public $ping;

	/**
	 *
	 * API status.
	 *
	 * @var bool
	 */
	public $status = true;

    /**
     * Curl error info
     *
     * @var string
     */
    public $error = null;

	/**
	 * Set api key and optionally API endpoint
	 *
	 * @param string $api_key API key.
	 * @param string $api_url API url.
	 * @param string $domain API domain.
	 */
	public function __construct() {
	}

	/**
	 * Check, if api works properly.
	 *
	 * @param null $api_url
	 * @param null $domain
	 *
	 * @return bool
	 */
	public function check_api( $api_url = null, $domain = null ) {

		if ( ! empty( $api_url ) ) {
			$this->api_url = $api_url;
		}
		if ( ! empty( $domain ) ) {
			$this->domain = $domain;
		}

		$this->ping = $this->call_ping();

		// To check, if Ping object is empty.
		$ping_array = (array) $this->ping;

		if ( empty( $ping_array ) ) {
			return true;
		}

		if ( $this->http_status !== $this->success_api_code && $this->invalid_apikey_code === $this->ping->code ) {
			return false;
		}

		return true;
	}

	/**
	 * We can modify internal settings.
	 *
	 * @param string $key key.
	 * @param string $value value.
	 */
	function __set( $key, $value ) {
		$this->{$key} = $value;
	}

	/**
	 * Ping to api.
	 * @return mixed
	 */
	public function call_ping() {
		return $this->call( 'accounts/' );
	}

	/**
	 * Return all campaigns
	 * @return mixed
	 */
	public function get_campaigns() {
		return $this->call( 'campaigns' );
	}

	/**
	 * get single campaign
	 *
	 * @param string $campaign_id retrieved using API
	 *
	 * @return mixed
	 */
	public function get_campaign( $campaign_id ) {
		return $this->call( 'campaigns/' . $campaign_id );
	}

	/**
	 * adding campaign
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	public function create_campaign( $params ) {
		return $this->call( 'campaigns', 'POST', $params );
	}

	/**
	 * add single contact into your campaign
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	public function add_contact( $params ) {
		return $this->call( 'contacts', 'POST', $params );
	}

	/**
	 * retrieving contact by id
	 *
	 * @param string $contact_id - contact id obtained by API
	 *
	 * @return mixed
	 */
	public function get_contact( $contact_id ) {
		return $this->call( 'contacts/' . $contact_id );
	}

	/**
	 * retrieving contact by params
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_contacts( $params = array() ) {
		return $this->call( 'contacts?' . $this->setParams( $params ) );
	}

	/**
	 * updating any fields of your subscriber (without email of course)
	 *
	 * @param       $contact_id
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function update_contact( $contact_id, $params = array() ) {
		return $this->call( 'contacts/' . $contact_id, 'POST', $params );
	}

	/**
	 * drop single user by ID
	 *
	 * @param string $contact_id - obtained by API
	 *
	 * @return mixed
	 */
	public function delete_contact( $contact_id ) {
		return $this->call( 'contacts/' . $contact_id, 'DELETE' );
	}

	/**
	 * retrieve account custom fields
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_custom_fields( $params = array() ) {
		return $this->call( 'custom-fields?' . $this->setParams( $params ) );
	}

	/**
	 * retrieve single custom field
	 *
	 * @param string $cs_id obtained by API
	 *
	 * @return mixed
	 */
	public function get_custom_field( $custom_id ) {
		return $this->call( 'custom-fields/' . $custom_id, 'GET' );
	}

	/**
	 * retrieve single custom field
	 *
	 * @param string $cs_id obtained by API
	 *
	 * @return mixed
	 */
	public function add_custom_field( $params = array() ) {
		return $this->call( 'custom-fields', 'POST', $params );
	}

	/**
	 * get single web form
	 *
	 * @param int $w_id
	 *
	 * @return mixed
	 */
	public function get_web_form( $w_id ) {
		return $this->call( 'webforms/' . $w_id );
	}

	/**
	 * retrieve all webforms
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_web_forms( $params = array() ) {
		return $this->call( 'webforms?' . $this->setParams( $params ) );
	}


	/**
	 * get single form
	 *
	 * @param int $form_id
	 *
	 * @return mixed
	 */
	public function get_form( $form_id ) {
		return $this->call( 'forms/' . $form_id );
	}

	/**
	 * retrieve all forms
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_forms( $params = array() ) {
		return $this->call( 'forms?' . $this->setParams( $params ) );
	}

	/**
	 * retrieve all forms
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_form_variants( $form_id ) {
		return $this->call( 'forms/' . $form_id . '/variants' );
	}


	public function update_account_info() {

		gr_update_option('account_first_name', $this->ping->firstName);
		gr_update_option('account_last_name', $this->ping->lastName);
		gr_update_option('account_email', $this->ping->email);
		gr_update_option('account_company_name', $this->ping->companyName);
		gr_update_option('account_state', $this->ping->state);
		gr_update_option('account_city', $this->ping->city);
		gr_update_option('account_street', $this->ping->street);
		gr_update_option('account_zip_code', $this->ping->zipCode);

		$country_code = null;

		if (isset($this->ping->countryCode) && isset($this->ping->countryCode->countryCode)) {
			$country_code = $this->ping->countryCode->countryCode;
		}

		gr_update_option('account_country_code', $country_code);

		$country_name = gr()->countries->get_country_by_code($country_code);
		gr_update_option('account_country_name', $country_name);
	}

	/**
	 * Curl run request
	 *
	 * @param null $api_method
	 * @param string $http_method
	 * @param array $params
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function call( $api_method = null, $http_method = 'GET', $params = array() ) {

		$certFile = getcwd() . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . 'cacert.pem';

		if ( empty( $api_method ) ) {
			return (object) array(
				'httpStatus'      => '400',
				'code'            => '1010',
				'codeDescription' => 'Error in external resources',
				'message'         => 'Invalid api method'
			);
		}

		$jparams = json_encode( $params );
		$url    = $this->api_url . '/' . $api_method;

		$headers = array(
			'X-Auth-Token: api-key ' . gr_get_value( 'api_key' ),
			'Content-Type: application/json',
			'User-Agent: PHP GetResponse client 0 . 0 . 1',
			'X-APP-ID: 74a0976d-5d56-486a-9f1c-84081608932d'
		);

		// for GetResponse 360
		if ( isset( $this->domain ) ) {
			$headers[] = 'X-Domain: ' . $this->domain;
		}

		//also as get method
		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_ENCODING       => 'gzip,deflate',
			CURLOPT_FRESH_CONNECT  => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT        => $this->timeout,
			CURLOPT_HEADER         => false,
			CURLOPT_HTTPHEADER     => $headers
		);

		if ( $http_method == 'POST' ) {
			$options[ CURLOPT_POST ]       = 1;
			$options[ CURLOPT_POSTFIELDS ] = $jparams;
		} else if ( $http_method == 'DELETE' ) {
			$options[ CURLOPT_CUSTOMREQUEST ] = 'DELETE';
		}

		if (file_exists($certFile))
		{
			$options[CURLOPT_CAINFO] = $certFile;
		}

		$curl = curl_init();
		curl_setopt_array( $curl, $options );
        $curlExec = curl_exec( $curl );

        if (false === $curlExec) {
            $this->error = curl_error($curl);
            return null;
        } else {
            $response = json_decode( $curlExec );
            $this->http_status = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

            if (isset($response->httpStatus) && 401 === $response->httpStatus) {
                throw Unauthorized_Request_Exception::createForInvalidResponseStatus($response->httpStatus, $response->message);
            }
        }

        curl_close( $curl );

		return (object) $response;
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 */
	private function setParams( $params = array() ) {
		$result = array();
		if ( is_array( $params ) ) {
			foreach ( $params as $key => $value ) {
				$result[ $key ] = $value;
			}
		}

		return http_build_query( $result );
	}

}