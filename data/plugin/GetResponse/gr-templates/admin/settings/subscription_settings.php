<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings form.
 */
?>

<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-subscription-settings' ) ?></h2>

<form method="post" action="<?php echo admin_url( 'admin.php?page=gr-integration-subscription-settings' ); ?>">
	<div id="poststuff" class="wrap">

		<div id="post-body" class="metabox-holder columns-2">

			<div id="post-body-content">

				<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

				<table class="widefat fixed">
					<!-- Start form -->
					<thead>
					<tr>
						<th><b><?php _e( 'Subscribe via Web Form', 'Gr_Integration' ); ?></b></th>
					</tr>
					</thead>

					<tbody>
					<tr>
						<td>
							<?php _e( 'To activate a GetResponse Web Form widget drag it to a sidebar or click on it.',
								'Gr_Integration' ); ?>
							<?php echo '<a href="' . admin_url( 'widgets.php' ) . '"><strong>' . __( 'Go to Widgets site',
									'Gr_Integration' ) . '</strong></a>'; ?>

						</td>
					</tr>
					</tbody>
				</table>
				<table class="widefat fixed second-table">

					<thead>
					<tr>
						<th><b><?php _e( 'Subscribe via Comment', 'Gr_Integration' ); ?></b></th>
					</tr>
					</thead>

					<tbody>
					<tr>
						<td>
							<?php gr_load_template( 'admin/settings/partials/subs_via_comment.php' ); ?>
						</td>
					</tr>
					</tbody>
				</table>
				<table class="widefat fixed second-table">

					<thead>
					<tr>
						<th><b><?php _e( 'Subscribe via Registration Page', 'Gr_Integration' ); ?></b></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							<?php
							// Load subscription via registration page.
							if ( true === gr()->buddypress->is_active() ) {
								gr_load_template( 'admin/settings/partials/subs_via_registration_page_bp.php' );
							} else {
								gr_load_template( 'admin/settings/partials/subs_via_registration_page.php' );
							}

							?>
						</td>
					</tr>
					<tr>
						<td>
							<input
								type="submit" name="Submit" class="button-primary"
								value="<?php _e( 'Save', 'Gr_Integration' ); ?>"/>
						</td>
					</tr>
					</tbody>
				</table>
			</div>

			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
				</div>

			</div>

		</div>
	</div>
</form>

