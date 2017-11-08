<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings form.
 */

$bp_registration_type = gr_get_option( 'bp_registration_on' );
?>

<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-buddypress' ) ?></h2>

<form method="post" action="<?php echo admin_url( 'admin.php?page=gr-integration-buddypress' ); ?>">
	<div class="wrap">

		<div id="poststuff" class="wrap">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content">

					<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

					<table class="widefat fixed">
					<thead>
						<tr>
							<th><b><?php _e( 'Subscribe via BuddyPress Registration Page', 'Gr_Integration' ); ?></b></th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							<table>
								<tbody>
								<tr>
									<th>
										<label class="GR_label" for="bp_registration_integration">
											<?php _e( 'Registration integration', 'Gr_Integration' ); ?>:
										</label>
									</th>
									<td>
										<select class="GR_select2" name="bp_registration_on" id="bp_registration_integration">
											<option value="0" <?php selected( $bp_registration_type, 0 ); ?>>
												<?php _e( 'Off', 'Gr_Integration' ); ?>
											</option>
											<option value="1" <?php selected( $bp_registration_type, 1 ); ?>>
												<?php _e( 'On', 'Gr_Integration' ); ?>
											</option>
										</select>

										<a class="gr-tooltip">
											<span class="gr-tip">
												<span>
													<?php _e( 'Allow subscriptions at the BuddyPress registration page',
															'Gr_Integration' ); ?>.
												</span>
											</span>
										</a>
									</td>
								</tr>
								</tbody>
							</table>

							<div id="bp_registration_show"
									<?php if ( '1' !== gr_get_option( 'bp_registration_on' ) ) : ?>
										style="display: none"
									<?php endif ?> >

								<table>
									<tbody>
									<tr>
										<th>
											<label class="GR_label" for="bp_registration_campaign">
												<?php _e( 'Target Campaign', 'Gr_Integration' ); ?>:
											</label>
										</th>
										<td>
											<?php
											gr_return_campaign_selector( gr_get_option( 'bp_registration_campaign' ),
													'bp_registration_campaign' );
											?>
										</td>
									</tr>
									<tr>
										<th>
											<label class="GR_label" for="bp_registration_label">
												<?php _e( 'Additional text:', 'Gr_Integration' ); ?>
											</label>
										</th>
										<td>
											<input
													class="GR_input2"
													type="text"
													name="bp_registration_label"
													id="bp_registration_label"
													value="<?php echo gr_get_option( 'bp_registration_label',
															__( 'Sign up to our newsletter', 'Gr_Integration' ) ) ?>"/>
										</td>
									</tr>
									<tr>
										<th>
											<label class="GR_label" for="bp_registration_checked">
												<?php _e( 'Subscribe checkbox checked by default', 'Gr_Integration' ); ?>
											</label>
										</th>
										<td>
											<input
													class="GR_checkbox"
													type="checkbox"
													name="bp_registration_checked"
													id="bp_registration_checked"
													value="1"
													<?php if ( '1' === gr_get_option( 'bp_registration_checked' ) ) : ?>
														checked="checked"
													<?php endif ?> />
										</td>
									</tr>
									</tbody>
								</table>
							</div>

						</td>
					</tr>
					<tr>
						<td>
							<!-- Submit form. -->
							<input
									type="submit" name="Submit" class="button-primary"
									value="<?php _e( 'Save', 'Gr_Integration' ); ?>"/>
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
	</div>
</form>

<script>
	jQuery('#bp_registration_integration').change(function () {
		var value = jQuery(this).val();
		if (value == '1') {
			jQuery('#bp_registration_show').show('slow');
		}
		else {
			jQuery('#bp_registration_show').hide('slow');
		}
	});
</script>