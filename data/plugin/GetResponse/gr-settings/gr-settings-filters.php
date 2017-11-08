<?php

add_filter( 'plugin_action_links', 'gr_modify_plugin_action_links', 11, 2 );
add_filter( 'plugin_row_meta', 'gr_plugin_row_meta', 10, 2 );

/**
 * Modify links in Admin section.
 */
function gr_modify_plugin_action_links( $links, $file ) {
	return gr()->settings->modify_plugin_action_links( $links, $file );
}

/**
 * Modify Row Meta data.
 */
function gr_plugin_row_meta($links, $file) {
    return gr()->settings->modify_plugin_row_meta( $links, $file );
}
