<?php

defined( 'ABSPATH' ) || exit;

$registration_type    = gr_get_option( 'registration_on' );
$bp_registration_type = gr_get_option( 'bp_registration_on' );
?>
<table>
	<tbody>
		<tr>
			<td>
				<label class="GR_label" for="registration_on">
					<?php _e( 'Registration integration', 'Gr_Integration' ); ?>:
				</label>
			</td>
			<td>
				<select class="GR_select2" name="registration_on" id="registration_integration">
					<option
						value="0" <?php selected( $registration_type, 0 ); ?>><?php _e( 'Off',
							'Gr_Integration' ); ?></option>
					<option
						value="1" <?php selected( $registration_type, 1 ); ?>><?php _e( 'On',
							'Gr_Integration' ); ?></option>
				</select>

				<a class="gr-tooltip">
					<span class="gr-tip">
						<span>
							<?php _e( 'Allow subscriptions at the registration page', 'Gr_Integration' ); ?>.
						</span>
					</span>
				</a>
			</td>
		</tr>
	</tbody>
</table>

<div id="registration_show"
	<?php if ( '1' !== gr_get_option( 'registration_on' ) ) : ?>
		style="display: none;"
	<?php endif ?> >
	<table>
		<tbody>
		<tr>
			<td>
				<label class="GR_label" for="registration_campaign">
					<?php _e( 'Target Campaign', 'Gr_Integration' ); ?>:
				</label>
			</td>
			<td>
				<?php
				gr_return_campaign_selector( gr_get_option( 'registration_campaign' ),
					'registration_campaign' );
				?>
			</td>
		</tr>

		<tr>
			<td>
				<label class="GR_label" for="registration_label">
					<?php _e( 'Additional text:', 'Gr_Integration' ); ?>
				</label>
			</td>
			<td>
				<input
					class="GR_api"
					type="text"
					name="registration_label"
					value="<?php echo gr_get_option( 'registration_label',
						__( 'Sign up to our newsletter!', 'Gr_Integration' ) ) ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<label class="GR_label" for="registration_checked">
					<?php _e( 'Subscribe checkbox checked by default', 'Gr_Integration' ); ?>
				</label>
			</td>
			<td>
				<input
					class="GR_checkbox"
					type="checkbox"
					name="registration_checked"
					value="1"
					<?php if ( '1' === gr_get_option( 'registration_checked' ) ) : ?>
						checked="checked"
					<?php endif ?> />
			</td>
		</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	jQuery('#registration_integration').change(function () {
		var value = jQuery(this).val();
		if (value == '1') {
			jQuery('#registration_show').show('slow');
		}
		else {
			jQuery('#registration_show').hide('slow');
		}
	});
</script>