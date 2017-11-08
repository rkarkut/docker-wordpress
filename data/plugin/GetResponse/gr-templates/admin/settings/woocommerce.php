<?php

defined( 'ABSPATH' ) || exit;

$checked = gr_get_option( 'checkout_on' );

/**
 * Display Settings form.
 */
?>

<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-woocommerce' ) ?></h2>

<form method="post" action="<?php echo admin_url( 'admin.php?page=gr-integration-woocommerce' ); ?>">
	<div id="poststuff" class="wrap">

		<div id="post-body" class="metabox-holder columns-2">

			<div id="post-body-content">

                <?php if (gr()->grwp_plugin_is_active()): ?>

                <table class="widefat fixed">
                    <thead>
                    <tr>
                        <th><b><?php _e( 'Subscribe via Checkout Page (WooCommerce)', 'Gr_Integration' ); ?></b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php _e('This section is disabled due to GetResponse WooCommerce plugin is active.', 'Gr_Integration'); ?>
                        </td>
                        </tr>
                    </tbody>
                </table>

                <?php else : ?>

				<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

				<table class="widefat fixed">
				<thead>
				<tr>
					<th><b><?php _e( 'Subscribe via Checkout Page (WooCommerce)', 'Gr_Integration' ); ?></b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>

						<table>
							<tbody>
							<tr>
								<td>
									<label class="GR_label" for="checkout_on">
										<?php _e( 'Checkout integration', 'Gr_Integration' ); ?>:
									</label>
								</td>
								<td>
									<select class="GR_select2" name="checkout_on" id="checkout_integration">
										<option value="0" <?php selected( $checked, 0 ); ?>>
											<?php _e( 'Off', 'Gr_Integration' ); ?>
										</option>
										<option value="1" <?php selected( $checked, 1 ); ?>>
											<?php _e( 'On', 'Gr_Integration' ); ?>
										</option>
									</select>

									<a class="gr-tooltip">
								<span class="gr-tip">
									<span>
										<?php _e( 'Allow subscriptions at the checkout stage.', 'Gr_Integration' ); ?>
									</span>
								</span>
									</a>
								</td>
							</tr>
						</table>

						<div id="checkout_show" <?php if ( '1' !== $checked ) : ?>style="display: none;"<?php endif ?>>

							<table>
								<tbody>
								<tr>
									<td>
										<label class="GR_label" for="checkout_campaign">
											<?php _e( 'Target campaign', 'Gr_Integration' ); ?>:
										</label>
									</td>
									<td>
										<?php gr_return_campaign_selector( gr_get_value( 'checkout_campaign' ),
											'checkout_campaign' ); ?>
									</td>
								</tr>

								<tr>
									<td>
										<label class="GR_label" for="comment_label">
											<?php _e( 'Additional text:', 'Gr_Integration' ); ?>
										</label>
									</td>
									<td>
										<input
											class="GR_input2"
											type="text"
											name="checkout_label"
											value="<?php echo gr_get_option( 'checkout_label',
												__( 'Sign up to our newsletter!', 'Gr_Integration' ) ) ?>"/>
									</td>
								</tr>

								<tr>
									<td>
										<label class="GR_label" for="checkout_checked">
											<?php _e( 'Sign up box checked by default', 'Gr_Integration' ); ?>
										</label>
									</td>
									<td>
										<input
											class="GR_checkbox"
											type="checkbox"
											name="checkout_checked"
											value="1"
											<?php if ( '1' === gr_get_option( 'checkout_checked' ) ) : ?>
												checked="checked"
											<?php endif ?>/>
									</td>
								</tr>

								<tr>
									<td>
										<label class="GR_label" for="sync_order_data">
											<?php _e( 'Map custom fields:', 'Gr_Integration' ); ?>
										</label>
									</td>
									<td>
										<input
											class="GR_checkbox"
											type="checkbox"
											name="sync_order_data"
											id="sync_order_data"
											value="1"
											<?php if ( '1' === gr_get_option( 'sync_order_data' ) ) : ?>
												checked="checked"
											<?php endif ?>/>

										<a class="gr-tooltip">
									<span class="gr-tip" style="width:170px">
										<span>
											<?php _e( 'Check to update customer details. Each input can be max. 32 characters and include lowercase, a-z letters, digits or underscores. Incorrect or empty entries won’t be added.',
												'Gr_Integration' ); ?>
										</span>
									</span>
										</a>
									</td>
								</tr>

								</tbody>
							</table>

							<!-- CUSTOM FIELDS PREFIX - CHECKOUT SUBSCRIPTION -->
							<div id="customNameFields" style="display: none;">
								<div class="gr-custom-field">
									<select class="jsNarrowSelect" name="custom_field" multiple="multiple">
										<?php
										foreach ( gr()->woocommerce->biling_fields as $value => $filed ) {
											$custom     = gr_get_option( $value );
											$field_name = ( $custom ) ? $custom : $filed['name'];
											echo '<option data-inputvalue="' . $field_name . '" value="' . $value . '" id="' . $filed['value'] . '"', ( $filed['default'] == 'yes' || $custom ) ? ' selected="selected"' : '', $filed['default'] == 'yes' ? ' disabled="disabled"' : '', '>', $filed['name'], '</option>';
										} ?>
									</select>
								</div>
							</div>

					</td>
				</tr>
				<tr>
					<td>
						<!-- Submit form. -->
						<input type="submit" name="Submit" class="button-primary"
						       value="<?php _e( 'Save', 'Gr_Integration' ); ?>"/>
					</td>
				</tr>
				</tbody>
			</table>

                <?php endif ?>
			</div>

			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable"></div>
			</div>

		</div>
	</div>
</form>

<script>
	jQuery('#checkout_integration').change(function () {
		var value = jQuery(this).val();
		if (value == '1') {
			jQuery('#checkout_show').slideDown();
		}
		else {
			jQuery('#checkout_show').slideUp();
		}
	});

	var sod = jQuery('#sync_order_data'), cfp = jQuery('#customNameFields');
	if (sod.prop('checked') == true) {
		cfp.show();
	}
	sod.change(function () {
		cfp.toggle('slow');
	});

	jQuery('.jsNarrowSelect').selectNarrowDown();
</script>
