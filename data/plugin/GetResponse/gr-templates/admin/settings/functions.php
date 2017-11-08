<?php

defined( 'ABSPATH' ) || exit;

/**
 * Return campaign selector.
 *
 * @param array $campaigns campaigns.
 * @param string $current_campaign current campaign.
 * @param string $name name.
 */
function gr_return_campaign_selector( $current_campaign, $name ) {

	$campaigns = gr()->settings->campaigns;

	if ( empty( $campaigns ) ) {
		_e( 'No Campaigns.', 'Gr_Integration' );

		return;
	}
	?>
	<select name="<?php echo $name; ?>" id="<?php echo $name; ?>" class="GR_select">
		<?php
		foreach ( $campaigns as $campaign ) {
			echo '<option value="' . $campaign->campaignId . '" id="' . $campaign->campaignId . '"', $current_campaign == $campaign->campaignId ? ' selected="selected"' : '', '>', $campaign->name, '</option>';
		}
		?>
	</select>
<?php }

/**
 * Get WP details list.
 */
function gr_get_wp_details_list() {
	echo "Version : " . get_bloginfo( 'version' ) . "\n";
	echo "Charset : " . get_bloginfo( 'charset' ) . "\n";
	echo "Url : " . get_bloginfo( 'url' ) . "\n";
	echo "Language : " . get_bloginfo( 'language' ) . "\n";
	echo "PHP : " . phpversion() . "\n";
}

/**
 * Return list of active plugins
 */
function gr_get_active_plugins_list() {
	echo "Active plugins:\n";
	foreach ( get_plugins() as $plugin_name => $plugin_details ) {
		if ( is_plugin_active( $plugin_name ) === true ) {
			foreach ( $plugin_details as $details_key => $details_value ) {
				if ( in_array( $details_key, array( 'Name', 'Version', 'PluginURI' ) ) ) {
					echo $details_key . " : " . $details_value . "\n";
				}
			}
			echo "Path : " . $plugin_name . "\n";
		}
	}
}

/**
 * Return list of active plugins
 */
function gr_get_gr_plugin_details_list() {
	echo "Getresponse-integration details:\n";
	$details = gr()->db->get_gr_plugin_details();
	if ( empty( $details ) ) {
		return;
	}

	foreach ( $details as $detail ) {
		echo str_replace( gr_prefix(), '',
				$detail->option_name ) . " : " . $detail->option_value . "\n";
	}
}

/**
 * Return active widgets
 */
function gr_get_widgets_list() {
	echo "Widgets:\n";
	$widgets = get_option( 'sidebars_widgets' );
	echo serialize( $widgets );
}

/**
 * Display GetResponse blog 10 RSS links
 */
function gr_rss_feeds() {

	$lang     = get_bloginfo( "language" ) == 'pl-PL' ? 'pl' : 'com';
	$feed_url = 'http://blog.getresponse.' . $lang . '/feed';

	$num = 10; // numbers of feeds:
	include_once( ABSPATH . WPINC . '/feed.php' );
	$rss = fetch_feed( $feed_url );

	if ( is_wp_error( $rss ) ) {
		_e( 'No rss items, feed might be broken.', 'Gr_Integration' );
	} else {
		$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );

		// If the feed was erroneously
		if ( ! $rss_items ) {
			$md5 = md5( $feed_url );
			delete_transient( 'feed_' . $md5 );
			delete_transient( 'feed_mod_' . $md5 );
			$rss       = fetch_feed( $feed_url );
			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
		}

		$content = '';
		if ( ! $rss_items ) {
			$content .= '<p>' . _e( 'No rss items, feed might be broken.',
					'Gr_Integration' ) . '</p>';
		} else {
			foreach ( $rss_items as $item ) {
				$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), $protocolls = null, 'display' ) );
				$content .= '<p>';
				$content .= '<a class="GR_rss_a" href="' . $url . '" target="_blank">' . esc_html( $item->get_title() ) . '</a> ';
				$content .= '</p>';
			}
		}
		$content .= '';
		echo $content;
	}
}

/**
 * Load tabs.
 *
 * * @param string $active
 *
 * @return mixed
 */
function gr_get_admin_tabs( $active = 'gr-integration' ) {
	return gr()->gr_core_admin->get_admin_tabs( $active );
}
