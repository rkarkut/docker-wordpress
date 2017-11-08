<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class GR_Core_Admin
 *
 * Core Admin class.
 */
class GR_Settings {

    const RATE_PLUGIN_LINK = 'http://wordpress.org/support/view/plugin-reviews/getresponse-integration?rating=5#postform';
    const FAQ_LINK = 'https://wordpress.org/plugins/getresponse-integration/faq/';

	/**
	 * Admin URL.
	 * @var string
	 */
	public $admin_url = '';

	/**
	 * Admin directory path.
	 *
	 * @var string
	 */
	public $admin_dir = '';

	/**
	 * Settings page name.
	 *
	 * @var string
	 */
	public $page_url = 'admin.php?page=gr-integration-status';

	/**
	 *
	 * Plugin version simple format.
	 *
	 * @var string
	 */
	public $js_plugin_version = '303';

	/**
	 * GR_Core_Admin constructor.
	 */
	public function __construct() {
		$this->setup_globals();
	}

	/**
	 * Set object variables.
	 */
	private function setup_globals() {

		$this->admin_url = trailingslashit( gr()->plugin_dir . 'gr-core/admin' );
		$this->admin_dir = trailingslashit( gr()->plugin_dir . 'gr-core/admin' );
	}

	/**
	 * Modify links in Admin section.
	 *
	 * @param array $links array of links.
	 * @param string $file file name.
	 *
	 * @return array
	 */
	public function modify_plugin_action_links( $links, $file ) {

		if ( gr()->basename !== $file ) {
			return $links;
		}

		$settings = '<a href="' . $this->page_url . '">' . __( 'Settings', 'Gr_Integration' ) . '</a>';
		$faq = '<a target="_blank" href="' . self::FAQ_LINK . '">' . __( 'FAQ', 'Gr_Integration' ) . '</a>';

		return array_merge( $links, array( 'settings' => $settings, 'faq' => $faq ) );

	}

	/**
	 * Set GetResponse360 domain To WebForm Url
	 *
	 * @param array $webforms webforms.
	 * @param string $api_domain api domain.
	 *
	 * @return mixed
	 */
	public function set_360_domain_to_webform_url( $webforms, $api_domain ) {
		if ( false === is_array( $webforms ) ) {
			return $webforms;
		}

		foreach ( $webforms as $webform ) {
			$webform->scriptUrl = 'http://' . $api_domain . '/' . $webform->scriptUrl;
		}

		return $webforms;
	}

    public function modify_plugin_row_meta($links, $file)
    {
        if ( gr()->basename !== $file ) {
            return $links;
        }
        $new_links = array('rate' => '<a href="' . self::RATE_PLUGIN_LINK . '" target="_blank">' . __( 'Rate this plugin', 'Gr_Integration' ) . '</a>');
        return array_merge( $links, $new_links );
	}
}

gr()->settings = new GR_Settings;
