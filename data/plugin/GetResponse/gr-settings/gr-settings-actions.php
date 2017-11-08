<?php

defined( 'ABSPATH' ) || exit;

add_action( 'gr_settings_run', 'gr_update_api_key' );
add_action( 'gr_settings_run', 'gr_update_common_settings' );
add_action( 'gr_settings_run', 'gr_load_campaigns' );

add_action( 'wp_ajax_gr-traceroute-submit', 'gr_traceroute_ajax_request' );
add_action( 'wp_ajax_gr-variants-submit', 'gr_variants_ajax_request' );
add_action( 'wp_ajax_gr-forms-submit', 'gr_forms_ajax_request' );
add_action( 'wp_ajax_gr-webforms-submit', 'gr_webforms_ajax_request' );

add_action( 'admin_head', 'gr_js_shortcodes' );

add_action( 'init', 'gr_buttons' );

add_filter( 'admin_footer_text', 'gr_admin_footer_text' );

/**
 * Update API Key settings.
 */
function gr_update_api_key() {

	if ( isset( $_POST['api_key'] ) && empty( $_POST['api_key'] ) ) {
		gr()->add_error_message( 'API Key cannot be empty.' );

		return false;
	}

	if ( null === gr_post( 'api_key' ) ) {
		return false;
	}

	$api_key = gr_post( 'api_key' );

	if ( null !== gr_post( 'getresponse_360_account' ) ) {
		$api_url    = gr_get_value( 'api_url' );
		$api_domain = gr_get_value( 'api_domain' );

		if ( 0 === strlen( $api_url ) ) {
			gr()->add_error_message( 'API URL cannot be empty.' );
            return;
		}

		if ( 0 === strlen( $api_domain ) ) {
			gr()->add_error_message( 'Domain cannot be empty.' );
            return;
		}

		if ( ! empty( $api_domain ) ) {
			$url_data             = parse_url( $api_url );
			gr()->traceroute_host = $url_data['host'];
		}
	} else {

		$api_url = null;
		$api_domain = null;

		gr_delete_option( 'api_url' );
		gr_delete_option( 'api_domain' );
	}

	try {
        gr()->api->status = gr()->api->check_api($api_url, $api_domain);

        //check for curl errors
        if ( false === empty(gr()->api->error) ) {
            gr()->add_error_message(gr()->api->error);

            return false;
        }

        // Check, if API is working correctly.
        if ( false === gr()->api->status ) {

            return false;
        }

        $ping_array = (array) gr()->api->ping;

        // Dziwny myk - jak jest pusty API Ping to znaczy ze API Key jest nieprawidlowy
        if ( isset(gr()->api->status) && true === gr()->api->status && (isset(gr()->api->ping->code) && gr()->api->ping->code === gr()->api->invalid_apikey_code || empty( $ping_array ) )) {
            gr()->add_error_message( 'Invalid API Key.' );

            return false;
        }

        gr()->api->update_account_info();

        gr_update_option( 'api_key', $api_key );
        gr_update_option( 'api_url', $api_url );
        gr_update_option( 'api_domain', $api_domain );
        gr_update_option( 'getresponse_360_account', gr_post( 'getresponse_360_account' ) );

        gr()->add_success_message(__('You have been connected', 'Gr_Integration'));

    } catch (Unauthorized_Request_Exception $e) {
	    gr()->disconnect_integration();
        gr()->add_error_message(__($e->getMessage(), 'Gr_Integration'));
    }
}

/**
 * Comment campaign send through form.
 */
function gr_update_common_settings() {

	if ( null === gr_post( 'comment_on' ) && null === gr_post( 'registration_on' ) ) {
		return false;
	}

	$post_fields = array(
		'comment_on',
		'comment_campaign',
		'comment_label',
		'registration_on',
		'registration_campaign',
		'registration_label',
		'comment_checked',
		'fields_prefix',
		'registration_checked'
	);

	foreach ( $post_fields as $field ) {
		gr_update_option( $field, gr_post( $field ) );
	}

	gr()->add_success_message(__('Settings saved', 'Gr_Integration'));


}

/**
 * GetResponse MCE buttons
 */
function gr_buttons() {
	add_filter( 'mce_buttons', 'gr_register_buttons' );
	add_filter( 'mce_external_plugins', 'gr_add_buttons' );
}

/**
 * Register buttons.
 *
 * @param array $buttons buttons.
 */
function gr_register_buttons( $buttons ) {
	array_push(
		$buttons,
		'separator',
		'GrShortcodes'
	);

	return $buttons;
}

/**
 * Add buttons.
 *
 * @param array $plugin_array plugins.
 */
function gr_add_buttons( $plugin_array ) {
	global $wp_version;

	$url = gr()->asset_path . '/js/gr-plugin_3_8.js?v' . gr()->settings->js_plugin_version;

	if ( $wp_version >= 3.9 ) {
		$url = gr()->asset_path . '/js/gr-plugin.js?v' . gr()->settings->js_plugin_version;
	}

	$plugin_array['GrShortcodes'] = $url;

	return $plugin_array;
}


/**
 * Add js variables.
 */
function gr_js_shortcodes() {

	$allowedPages = array( 'post.php', 'post-new.php' );
	global $pagenow;

	if ( false === in_array( $pagenow, $allowedPages ) ) {
		return false;
	}

	$api_key    = gr_get_option( 'api_key' );
	$api_domain = gr_get_option( 'api_domain' );

	$webforms   = null;
	$forms      = null;
	$campaingns = null;

	if ( false === empty( $api_key ) ) {

	    try {
            $webforms = gr()->api->get_web_forms(['sort' => ['name' => 'asc']]);
            $forms = gr()->api->get_forms(['sort' => ['name' => 'asc']]);
            $api_key = 'true';
            $campaingns = gr()->api->get_campaigns(); // for 3.8 version
        } catch (Unauthorized_Request_Exception $e) {
            gr()->disconnect_integration();
        }
	} else {
        $api_key = 'false';
	}

	if ( strlen( $api_domain ) > 0 ) {

		$webforms = gr()->settings->set_360_domain_to_webform_url( $webforms, $api_domain );
		$forms    = gr()->settings->set_360_domain_to_webform_url( $forms, $api_domain );
	}

	$webforms   = json_encode( $webforms );
	$forms      = json_encode( $forms );
	$campaingns = json_encode( $campaingns ); // for 3.8 version
	?>
	<script type="text/javascript">
		var my_webforms = <?php echo $webforms; ?>;
		var my_forms = <?php echo $forms; ?>;
		var my_campaigns = <?php echo $campaingns;  // for 3.8 version ?>;
		var text_forms = '<?php echo __('New Forms', 'Gr_Integration'); ?>';
		var text_webforms = '<?php echo __('Old Web Forms', 'Gr_Integration'); ?>';
		var text_no_forms = '<?php echo __('No Forms', 'Gr_Integration'); ?>';
		var text_no_webforms = '<?php echo __('No Web Forms', 'Gr_Integration'); ?>';
		var api_key = <?php echo $api_key; ?>;
	</script>
	<?php
}

/**
 * Load client Campaigns.
 */
function gr_load_campaigns() {

	if ( null === gr_get_option( 'api_key' ) ) {
		return false;
	}

	try {
        gr()->settings->campaigns = gr()->api->get_campaigns();
    } catch (Unauthorized_Request_Exception $e) {
        gr()->disconnect_integration();
    }
}


/**
 * GR Traceroute Ajax request.
 */
function gr_traceroute_ajax_request() {
	$response = '';
	if ( preg_match( '/^win.*/i', PHP_OS ) ) {
		exec( 'tracert ' . gr()->traceroute_host, $out, $code );
	} else {
		exec( 'traceroute -m15 ' . gr()->traceroute_host . ' 2>&1', $out, $code );
	}

	if ( $code && is_array( $out ) ) {
		$response = __( 'An error occurred while trying to traceroute',
				'Gr_Integration' ) . ': <br />' . join( "\n", $out );
	}

	if ( ! empty( $out ) ) {
		foreach ( $out as $line ) {
			$response .= $line . "<br/>";
		}
	}

	$response = wp_json_encode( array( 'success' => $response ) );
	header( 'Content-Type: application/json' );
	echo $response;
	exit;
}

/**
 * GR Variants Ajax request.
 */
function gr_variants_ajax_request() {

	$response = json_encode( array( 'error' => 'No variants' ) );

	if ( null !== gr_get( 'form_id' ) ) {

	    try {
            $variants = gr()->api->get_form_variants(gr_get('form_id'));

            if ( ! empty( $variants ) ) {
                $response = json_encode( array( 'success' => $variants ) );
            }
        } catch (Unauthorized_Request_Exception $e) {
            gr()->disconnect_integration();
            $response = json_encode( array( 'error' => $e->getMessage() ) );
        }
	}

	header( 'Content-Type: application/json' );
	echo $response;
	exit;
}

/**
 * GR Forms Ajax request.
 */
function gr_forms_ajax_request() {
    try {
        $forms = gr()->api->get_forms(['sort' => ['name' => 'asc']]);
        $response = json_encode(['success' => $forms]);
    } catch (Unauthorized_Request_Exception $e) {
        gr()->disconnect_integration();
        $response = json_encode(['error' => $e->getMessage()]);
    }

    header('Content-Type: application/json');
    echo $response;
    exit;
}

/**
 * GR Webforms Ajax request.
 */
function gr_webforms_ajax_request() {
    try {
        $forms = gr()->api->get_web_forms(['sort' => ['name' => 'asc']]);
        $response = json_encode(['success' => $forms]);
    } catch (Unauthorized_Request_Exception $e) {
        gr()->disconnect_integration();
        $response = json_encode(['error' => $e->getMessage()]);
    }

    header('Content-Type: application/json');
    echo $response;
    exit;
}

function gr_admin_footer_text( $footer_text ) {

	$pages = array(
		'getresponse_page_gr-integration-status',
		'getresponse_page_gr-integration-subscription-settings',
		'getresponse_page_gr-integration-web-form',
		'getresponse_page_gr-integration-buddypress',
		'getresponse_page_gr-integration-woocommerce',
		'getresponse_page_gr-integration-help',
		'getresponse_page_gr-integration-error'
	);

	$current_screen = get_current_screen();

	if ( false === in_array( $current_screen->id, $pages ) ) {
		return $footer_text;
	}

	gr_load_template('admin/settings/footer.php');
}