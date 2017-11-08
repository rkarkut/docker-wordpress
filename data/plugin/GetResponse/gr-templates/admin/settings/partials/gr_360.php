<?php

/**
 * GetResponse 360 template.
 */
defined( 'ABSPATH' ) || exit;

?>

<table>
	<tbody>
	<!-- GetResponse 360 -->
		<tr>
			<td>
				<label class="GR_label" for="api_key">
					<?php _e( 'GetResponse 360', 'Gr_Integration' ); ?>:
				</label>
			</td>
			<td>
				<input
					class="GR_checkbox"
					type="checkbox"
					name="getresponse_360_account"
					id="getresponse_360_account"
					value="1"
					<?php if ( '1' === gr_get_option( 'getresponse_360_account' ) ) : ?>
						checked="checked"
					<?php endif ?> />

				<a class="gr-tooltip">
					<span class="gr-tip">
						<span>
							<?php _e( 'For GetResponse 360 accounts', 'Gr_Integration' ); ?>.
						</span>
					</span>
				</a>
			</td>
		</tr>
	</tbody>
</table>

<div id="getresponse_360_account_options"
	<?php if ( '1' !== gr_get_option( 'getresponse_360_account' ) ) : ?> style="display: none" <?php endif ?>
>
	<table>
		<tbody>
		<!-- GetResponse 360 -->
			<tr valign="top">
				<th scope="row">
					<label class="GR_label" for="api_url">
						<?php _e( 'Type', 'Gr_Integration' ); ?>:
					</label>
				</th>
				<td>
					<select class="" name="api_url" id="api_url">
						<option
							value="<?php echo gr()->api->api_url_360_pl; ?>" <?php selected( gr_get_option( 'api_url' ),
							gr()->api->api_url_360_pl ); ?>><?php _e( 'GetResponse360 PL',
								'Gr_Integration' ); ?></option>
						<option
							value="<?php echo gr()->api->api_url_360_com; ?>" <?php selected( get_option( 'api_url' ),
							gr()->api->api_url_360_com ); ?>><?php _e( 'GetResponse360 COM',
								'Gr_Integration' ); ?></option>
					</select>
					<a class="gr-tooltip">
					<span class="gr-tip">
						<span>
							<?php _e( 'This data is available from your account manager.', 'Gr_Integration' ); ?>
						</span>
					</span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<label class="GR_label" for="api_domain">
						<?php _e( 'Domain', 'Gr_Integration' ); ?>:
					</label>
				</td>
				<td>
					<input
						class="GR_api"
						type="text"
						name="api_domain"
						value="<?php echo gr_get_option( 'api_domain' ) ?>"/>

					<a class="gr-tooltip">
						<span class="gr-tip">
							<span>
								<?php _e( 'Enter your domain without protocol http:// eg: "yourdomainname.com"',
									'Gr_Integration' ); ?>
							</span>
						</span>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script>
	if (window.canRunAds === undefined) {
		jQuery('#GrDetails').append('\nAdBlock : active');
	}

	function copyText(element) {
		element.focus();
		element.select();
	}

	jQuery('#getresponse_360_account').change(function () {
		var value = jQuery('#getresponse_360_account:checked').val();
		if (value == '1') {
			jQuery('#getresponse_360_account_options').show();
		}
		else {
			jQuery('#getresponse_360_account_options').hide();
		}
	});
</script>