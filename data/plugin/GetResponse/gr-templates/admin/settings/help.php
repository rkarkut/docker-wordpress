<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings form.
 */
?>

<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-help' ) ?></h2>

<div id="poststuff" class="wrap">

	<div id="post-body" class="metabox-holder columns-2">

		<div id="post-body-content">

			<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

			<table class="widefat fixed">
				<thead>
				<tr>
					<td><b><?php _e( 'GetResponse support', 'Gr_Integration' ); ?></b></td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
					<?php
					echo sprintf( __( 'Make sure to look at the our WordPress plugin: %s documentation %s or use the %s support forums %s on WordPress.org.', 'Gr_Integration' ),
						'<a title="GetResponse documentation" target="_blank" href="http://connect.getresponse.com/integration/wordpress-integration-2">',
						'</a>',
						'<a target="_blank" title="GetResponse support forum on WordPress.org" href="https://wordpress.org/support/plugin/getresponse-integration">',
						'</a>');
					?>
					</td>
				</tr>
				</tbody>
			</table>

			<table class="widefat fixed second-table">
			<thead>
			<tr>
				<th><b><?php _e( 'Having problems with the plugin?', 'Gr_Integration' ); ?></b></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<?php _e( 'You can drop us a line including the following details and we\'ll do what we can. ',
						'Gr_Integration' ); ?>

					<a href="<?php echo gr()->contact_form_url ?>" target="_blank">
						<strong><?php _e( 'CONTACT US', 'Gr_Integration' ) ?></strong>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<textarea id="GrDetails" onclick="copyText(this)" data-copytarget="#GrDetails"
					          style="width: 100%; height: 350px; resize:vertical ;">
					<?php
					gr_get_wp_details_list();
					gr_get_active_plugins_list();
					gr_get_gr_plugin_details_list();
					gr_get_widgets_list();
					?>
					</textarea>
					<div id="GrCopied" style="float: right; display: none;"><?php _e( 'Copied', 'Gr_Integration' ); ?></div>
				</td>
			</tr>
			</tbody>
		</table>
		</div>

		<div id="postbox-container-1" class="postbox-container">
			<div id="side-sortables" class="meta-box-sortables ui-sortable"></div>
		</div>

	</div>
</div>

<script>
	(function () {
		'use strict';
		document.body.addEventListener('click', copy, true);
		function copy(e) {
			var t = e.target, c = t.dataset.copytarget,
				inp = (c ? document.querySelector(c) : null);
			if (inp && inp.select) {
				inp.select();
				try {
					document.execCommand('copy');
					inp.blur();
					jQuery('#GrCopied').show('slow');
					setTimeout(function () {
						jQuery('#GrCopied').hide();
					}, 1500);
				}
				catch (err) {
					alert('please press Ctrl/Cmd+C to copy');
				}
			}
		}
	})();

	if (window.canRunAds === undefined) {
		jQuery('#GrDetails').append('\nAdBlock : active');
	}

	function copyText(element) {
		element.focus();
		element.select();
	}

	jQuery('#getresponse_360_account').change(function () {
		var value = jQuery('#getresponse_360_account:checked').val();
		var selector = jQuery('#getresponse_360_account_options');
		if (value == '1') {
			selector.show();
		}
		else {
			selector.hide();
		}
	});
</script>
