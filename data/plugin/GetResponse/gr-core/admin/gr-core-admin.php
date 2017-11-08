<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class GR_Core_Admin
 *
 * Core Admin class.
 */
class GR_Core_Admin {

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
	public $settings_page = 'options-general.php';

	/**
	 * GR_Core_Admin constructor.
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_filters();
	}

	/**
	 * Set object variables.
	 */
	private function setup_globals() {

		$this->admin_url = trailingslashit( gr()->plugin_dir . 'gr-core/admin' );
		$this->admin_dir = trailingslashit( gr()->plugin_dir . 'gr-core/admin' );
	}

	/**
	 * Set filters.
	 */
	private function setup_filters() {
		// Add Settings page to links.
		add_filter( 'plugin_action_links', array( $this, 'modify_plugin_action_links' ), 11, 2 );
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
		return $links;
	}

	/**
	 * Get Admin tabs.
	 *
	 * * @param $active
	 *
	 * @return string
	 */
	public function get_admin_tabs( $active ) {

		$html_tabs = '';

		$tab_links   = array();
		$tab_links[] = array(
			'url'   => 'gr-integration-status',
			'name'  => __('Status & Configuration', 'Gr_Integration')
		);

		if ( null !== gr_get_option( 'api_key' ) ) {

			$tab_links[] = array(
				'url'   => 'gr-integration-subscription-settings',
				'name'  => __('Subscription Settings', 'Gr_Integration')

			);

			$tab_links[] = array(
				'url'   => 'gr-integration-web-form',
				'name'  => __('WebForm Shortcode', 'Gr_Integration')
			);

			if ( gr()->buddypress->is_active() ) {
				$tab_links[] = array(
					'url'   => 'gr-integration-buddypress',
					'name'  => __('BuddyPress Integration', 'Gr_Integration')
				);
			}

			if ( gr()->woocommerce->is_active() ) {
				$tab_links[] = array(
					'url'   => 'gr-integration-woocommerce',
					'name'  => __('WooCommerce Integration', 'Gr_Integration')
				);
			}

            if ( gr()->contactForm7->is_active() ) {
                $tab_links[] = array(
                    'url'   => 'gr-integration-contact-form-7',
                    'name'  => 'Contact Form 7 Integration'
                );
            }
		}

		$tab_links[] = array(
			'url'   => 'gr-integration-help',
			'name'  => __('Help', 'Gr_Integration')
		);

		foreach ( $tab_links as $tab ) {

			$url = admin_url( add_query_arg( array( 'page' => $tab['url'] ), 'admin.php' ) );

			$activeTab = null;

			if ( $active === $tab['url'] ) {
				$activeTab = 'nav-tab-active';
			}

			$class = 'nav-tab ' . $activeTab;
            $icon = '';

            if (isset($tab['class'])) {
                $icon = '<span class="dashicons ' . $tab['class'] . '"></span>';
            }

			$html_tabs .= '<a class="' . $class . '" href="' . $url . '">' . $icon . $tab['name'] . '</a>';
		}

		return $html_tabs;
	}
}

/**
 * Default class function.
 */
function gr_core_admin() {
	gr()->gr_core_admin = new GR_Core_Admin;
}

/**
 * Create class instance.
 */
gr_core_admin();
