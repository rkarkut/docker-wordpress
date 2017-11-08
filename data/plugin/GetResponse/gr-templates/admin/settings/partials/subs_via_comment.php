<?php

/**
 * Subscribe via comment template.
 */
defined( 'ABSPATH' ) || exit;

$comment_type = gr_get_option( 'comment_on' );

?>
<table>
	<tbody>
	<tr valign="top">
		<th scope="row">
			<label class="GR_label" for="comment_on">
				<?php _e( 'Comment integration', 'Gr_Integration' ); ?>:
			</label>
		</th>
		<td>
			<select class="GR_select2" name="comment_on" id="comment_integration">
				<option
					value="0" <?php selected( $comment_type, 0 ); ?>><?php _e( 'Off',
						'Gr_Integration' ); ?></option>
				<option
					value="1" <?php selected( $comment_type, 1 ); ?>><?php _e( 'On',
						'Gr_Integration' ); ?></option>
			</select>

			<a class="gr-tooltip">
				<span class="gr-tip">
					<span>
						<?php _e( 'Allow subscriptions when visitors comment', 'Gr_Integration' ); ?>.
					</span>
				</span>
			</a>
		</td>
	</tr>
	</tbody>
</table>

<div id="comment_show"
	<?php if ( '1' !== gr_get_option( 'comment_on' ) ) : ?>
		style="display: none"
	<?php endif ?> >
	<!-- CAMPAIGN TARGET -->


	<table>
		<tbody>
		<tr valign="top">
			<th scope="row">
				<label class="GR_label" for="comment_campaign">
					<?php _e( 'Target Campaign', 'Gr_Integration' ); ?>:
				</label>
			</th>
			<td>
				<?php
				// check if no errors
				gr_return_campaign_selector( gr_get_option( 'comment_campaign' ), 'comment_campaign' );
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label class="GR_label" for="comment_label">
					<?php _e( 'Additional text:', 'Gr_Integration' ); ?>
				</label>
			</th>
			<td>
				<input
					class="GR_api"
					type="text"
					name="comment_label"
					value="<?php echo gr_get_option( 'comment_label',
							__( 'Sign up to our newsletter!', 'Gr_Integration' ) ) ?>"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label class="GR_label" for="comment_checked">
					<?php _e( 'Subscribe checkbox checked by default', 'Gr_Integration' ); ?>
				</label>
			</th>
			<td>
				<input
					class="GR_checkbox"
					type="checkbox"
					name="comment_checked"
					value="1"
					<?php if ( '1' === gr_get_option( 'comment_checked' ) ) : ?>
						checked="checked"
					<?php endif ?>/>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">

	jQuery('#comment_integration').change(function () {
		var value = jQuery(this).val();

		if (value == '1') {
			jQuery('#comment_show').show('slow');
		}
		else {
			jQuery('#comment_show').hide('slow');
		}
	});
</script>