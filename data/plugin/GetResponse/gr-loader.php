<?php

/**
 * Plugin Name: GetResponse Integration Plugin
 * Plugin URI: http://wordpress.org/extend/plugins/getresponse-integration/
 * Description: This plug-in enables installation of a GetResponse fully customizable sign up form on your WordPress site or blog. Once a web form is created and added to the site the visitors are automatically added to your GetResponse contact list and sent a confirmation email. The plug-in additionally offers sign-up upon leaving a comment.
 * Version: 4.3.1
 * Author: GetResponse
 * Author URI: http://getresponse.com/
 * Author: GR Integration Team ;)
 * License: GPL2
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class GetResponse
 */
class GetResponse {

	/**
	 *
	 * Enable log data to file.
	 *
	 * @var bool
	 */
	public $enable_log = true;
	/**
	 * Plugin name.
	 *
	 * @var
	 */
	public $plugin_name;
	/**
	 * Plugin directory path.
	 */
	public $plugin_dir;

	/**
	 * Plugin base name.
	 */
	public $basename;

	/**
	 * Traceroute host.
	 *
	 * @var string
	 */
	public $traceroute_host = 'api.getresponse.com';
	/**
	 *
	 * URL to contact form.
	 *
	 * @var string
	 */
	public $contact_form_url = "https://app.getresponse.com/feedback.html?devzone=yes&lang=en";

	/**
	 *
	 * Log path.
	 *
	 * @var string
	 */
	public $log_path = '';
	/**
	 *
	 * GetResponse Settings instance.
	 *
	 * @var null
	 */
	public $settings = null;

	/**
	 * Name of settings page.
	 *
	 * @var string
	 */
	public $settings_page = 'gr-integration.php';

	/**
	 *
	 * API class instance.
	 *
	 * @var null
	 */
	public $api = null;

	/**
	 *
	 * WooCommerse class instance.
	 *
	 * @var null
	 */
	public $woocommerce = null;

	/**
	 *
	 * BuddyPress class instance.
	 *
	 * @var null
	 */
	public $buddypress = null;

	/**
	 *
	 * Page class instance.
	 *
	 * @var null
	 */
	public $page = null;

	/**
	 *
	 * Database handler.
	 *
	 * @var null
	 */
	public $db = null;

	/**
	 *
	 * Widget instance.
	 *
	 * @var null
	 */
	public $widget = null;

	/**
	 *
	 * Get Response Integration Widget instance.
	 *
	 * @var null
	 */
	public $int_widget = null;

	/**
	 *
	 * Asset path.
	 *
	 * @var null
	 */
	public $asset_path = null;

    /**
     *
     * ContactForm7 class instance.
     *
     * @var null
     */
	public $contactForm7 = null;

	/**
	 * Get instance of GetResponse class.
	 *
	 * @return GetResponse
	 */
	public static function instance() {
		static $instance;

		if ( null === $instance ) {
			$instance = new GetResponse;

			$instance->includes();
			$instance->setup_globals();
			$instance->setup_actions();
			$instance->setup_filters();
		}

		// Return GetResponse instance.
		return $instance;
	}

	public $error_messages = array();
	public $success_messages = array();

	/**
	 * Setup environment globals variables.
	 */
	private function setup_globals() {
		$this->plugin_dir = plugin_dir_path( __FILE__ );

		$this->basename    = basename( $this->plugin_dir ) . '/gr-loader.php';
		$this->plugin_name = 'GetResponse';
		$this->log_path    = $this->plugin_dir . 'log.txt';

		$url              = untrailingslashit( plugins_url( '/', __FILE__ ) );
		$this->asset_path = $url . '/gr-assets';

		$this->api = new GR_Api();
	}

	/**
	 *  Include files.
	 */
	private function includes() {

		// Core.
		require( $this->plugin_dir . 'gr-core/gr-core-loader.php' );

		// Template functions.
		require( $this->plugin_dir . 'gr-templates/admin/settings/functions.php' );

		// Woocommerce.
		require( $this->plugin_dir . 'gr-woocommerce/gr-woocommerce-loader.php' );

		// BuddyPress.
		require( $this->plugin_dir . 'gr-buddypress/gr-buddypress-loader.php' );
		// ContactForm7.
		require( $this->plugin_dir . 'gr-contact-form-7/gr-contact-form-7-loader.php' );

		// Settings.
		require( $this->plugin_dir . 'gr-settings/gr-settings-loader.php' );

		// Front Page.
		require( $this->plugin_dir . 'gr-page/gr-page-loader.php' );

		// Widgets.
		require( $this->plugin_dir . 'gr-widgets/gr-widgets-loader.php' );
	}

	/**
	 * Setup actions.
	 *
	 * There is only actions required in the whole plugin (admin + front site).
	 */
	private function setup_actions() {
		add_action( 'plugins_loaded', array( &$this, 'gr_langs' ) );
		add_shortcode( 'grwebform', array( &$this, 'show_webform_short_code' ) );
	}

	/**
	 * Show WebFrom short code.
	 *
	 * @param string $atts attributes.
	 *
	 * @return string
	 */
	public function show_webform_short_code( $atts ) {

		$params = shortcode_atts( array(
			'url'           => 'null',
			'css'           => 'on',
			'center'        => 'off',
			'center_margin' => '200',
			'variant'       => ''
		), $atts );

		$div_start = $div_end = '';
		if ( $params['center'] == 'on' ) {
			$div_start = '<div style="margin-left: auto; margin-right: auto; width: ' . $params['center_margin'] . 'px;">';
			$div_end   = '</div>';
		}

		$css = ( $params['css'] == "off" ) ? htmlspecialchars( "&css=1" ) : "";

		$variant_maps      = array( 'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7 );
		$params['variant'] = strtoupper( $params['variant'] );
		$variant           = ( in_array( $params['variant'],
			array_keys( $variant_maps ) ) ) ? htmlspecialchars( "&v=" . $variant_maps[ $params['variant'] ] ) : "";

		$params['url'] = $this->replace_https_to_http_if_ssl_on( $params['url'] );

		return $div_start . '<script type="text/javascript" src="' . $params['url'] . $css . $variant . '"></script>' . $div_end;
	}

	/**
	 * Replace https prefix in url if ssl is off
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	public function replace_https_to_http_if_ssl_on( $url ) {
		return ( ! empty( $url ) && ! is_ssl() && strpos( $url, 'https' ) === 0 ) ? str_replace( 'https', 'http',
			$url ) : $url;
	}

	/**
	 * Load GetResponse langs.
	 */
	public function gr_langs() {
		load_plugin_textdomain(
			'Gr_Integration',
			false,
			plugin_basename(dirname(__FILE__)) . '/gr-assets/langs'
		);
	}

	/**
	 * Setup fileters.
	 */
	private function setup_filters() {

	}

	/**
	 * Check, if file exists.
	 *
	 * * @param $template string template file source.
	 *
	 * @return bool
	 */
	public function locate_template( $template ) {

		$path = $this->plugin_dir . 'gr-templates/' . $template;

		if ( is_file( $path ) && file_exists( $path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Log data to file.
	 *
	 * * @param $data
	 *
	 */
	public function log( $data ) {

		if ( false === $this->enable_log ) {
			return;
		}

		if ( false === is_file( gr()->log_path ) ) {
			return;
		}

		if ( $fh = fopen( gr()->log_path, 'w' ) ) {

			if ( null === $data ) {
				$message = "\n";
			} else {
				$message = "\n" . date( 'Y-m-d H:i:s' ) . ' ' . (string) $data;
			}

			fwrite( $fh, $message, 1024 );
			fclose( $fh );
		}
	}

	/**
	 * Load template file.
	 *
	 * @param string $template template file source.
	 * @param array $params array of variables.
	 */
	public function load_template( $template, $params = array() ) {
		$path = $this->plugin_dir . 'gr-templates/' . $template;

		if ( false === empty( $params ) ) {
			extract( $params, EXTR_OVERWRITE );
		}

		require( $path );
	}

	/**
	 * Add error message to queue.
	 *
	 * * @param $message
	 *
	 */
	public function add_error_message( $message ) {
		if ( empty( $message ) ) {
			return;
		}
		gr()->error_messages[] = $message;
	}

	/**
	 * Add success message to queue.
	 *
	 * * @param $message
	 *
	 */
	function add_success_message( $message ) {
		if ( empty( $message ) ) {
			return;
		}
		gr()->success_messages[] = $message;
	}

	/**
	 * Check requirements.
	 *
	 */
	public function check_requirements() {

		if ( false === $this->valid_curl_extension() ) {
			return false;
		}

		if ( false === $this->valid_api() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check, if curl extension is available.
	 */
	public function valid_curl_extension() {
		if ( extension_loaded( 'curl' ) && is_callable( 'curl_init' ) ) {
			return true;
		}

		return false;
	}

	public function valid_api() {
		return $this->api->status;
	}

    public function grwp_plugin_is_active()
    {
        return (bool) get_option('woo_gr_woogr_checkout_on');
    }

    public function disconnect_integration()
    {
        $settings = array(
            'account_city',
            'account_company_name',
            'account_country_code',
            'account_country_name',
            'account_email',
            'account_first_name',
            'account_last_name',
            'account_state',
            'account_street',
            'account_zip_code',
            'api_domain',
            'api_key',
            'api_url',
            'getresponse_360_account'
        );

        foreach ($settings as $setting) {
            gr_update_option($setting, null);
        }

    }
}

/**
 * Function responsible for returning one instance of GetRetresponse Instance.
 *
 * @return GetResponse
 */
function gr() {
	return GetResponse::instance();
}

if ( defined( 'ABSPATH' ) and defined( 'WPINC' ) ) {
	if ( empty( $GLOBALS['GetResponseIntegration'] ) ) {
		$GLOBALS['GetResponseIntegration'] = gr();
	}
}

/**
 * Function to check if file exists.
 *
 * * @param $template string file source.
 *
 * @return bool
 */
function gr_locate_template( $template ) {
	return gr()->locate_template( $template );
}

/**
 * Load template file.
 *
 * @param string $template string file path.
 * @param array $params array of variables.
 */
function gr_load_template( $template, $params = array() ) {

	$is_template = gr_locate_template( $template );

	if ( false === $is_template ) {
		return false;
	}

	return gr()->load_template( $template, $params );
}

/**
 * Get prefix.
 *
 * * @param $val
 *
 * @return string
 */
function gr_prefix( $val = null ) {
	return 'gr_integrations_' . $val;
}

/**
 * Get option value with prefix.
 *
 * @param string $value value.
 * @param null $default default settings.
 *
 * @return mixed|void
 */
function gr_get_option( $value, $default = null ) {
	$result = get_option( gr_prefix( $value ), $default );

	if (false == empty($result)) {
		return $result;
	}

	return null;
}

/**
 * Get value if exists in global or database.
 *
 * * @param string $name - name of variable.
 *
 * @return string|null
 */
function gr_get_value( $name ) {
	if ( isset( $_GET[ $name ] ) ) {
		return $_GET[ $name ];
	}

	if ( isset( $_POST[ $name ] ) ) {
		return $_POST[ $name ];
	}

	$value = get_option( gr_prefix( $name ) );

	if ( false == empty ( $value ) ) {
		return $value;
	}

	return null;
}

/**
 * Update value with GetResponse prefix.
 *
 * * @param $name
 * @param $value
 *
 */
function gr_update_option( $name, $value ) {
	update_option( gr_prefix( $name ), $value );
}

/**
 * Delete value with GetResponse prefix.
 *
 * * @param $name
 * @param $value
 *
 */
function gr_delete_option( $name ) {
	delete_option( gr_prefix( $name ) );
}

/**
 * Get value from global $_GET array.
 *
 * * @param $name
 *
 * @return null
 */
function gr_get( $name ) {

	if ( false == isset( $_GET[ $name ] ) ) {
		return null;
	}

	if ( is_string( $_GET[ $name ] ) && 0 === strlen( $_GET[ $name ] ) ) {
		return null;
	}

	return $_GET[ $name ];
}

/**
 * Get value from global $_GET array.
 *
 * * @param $name
 *
 * @return null
 */
function gr_post( $name ) {

	if ( false == isset( $_POST[ $name ] ) ) {
		return null;
	}

	if ( is_string( $_POST[ $name ] ) && 0 === strlen( $_POST[ $name ] ) ) {
		return null;
	}

	if ( is_array( $_POST[ $name ] ) && 0 === count( $_POST[ $name ] ) ) {
		return false;
	}

	return sanitize_text_field(stripslashes($_POST[ $name ]));
}

/**
 * Log to file.
 *
 * * @param $val
 *
 */
function gr_log( $val ) {
	if ( true === is_array( $val ) ) {
		$val = serialize( $val );
	}
	gr()->log( $val );
}

/**
 * Display and die.
 *
 * * @param $val
 *
 */
function dd( $val ) {
	print '<pre>';
	print_r( $val );
	die;
}

/**
 * Url to error page.
 *
 * @return string|void
 */
function error_url() {
	return admin_url( add_query_arg( array( 'page' => 'page=gr-integration-error' ), 'admin.php' ) );
}