<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings form.
 */
?>
<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-status' ) ?></h2>

<form method="post" action="<?php echo admin_url( gr()->settings->page_url ); ?>">
	<div id="poststuff" class="wrap">

		<div id="post-body" class="metabox-holder columns-2">

			<div id="post-body-content">

				<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

				<table class="widefat fixed">
					<thead>
						<tr>
							<th><b><?php _e( 'GetResponse API Settings', 'Gr_Integration' ); ?></b></th>
                            <th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php
									// Load API form.
									gr_load_template( 'admin/settings/partials/api_key_form.php' );

									// Load GR-360 form.
									gr_load_template( 'admin/settings/partials/gr_360.php' ); ?>

                                <table>
                                    <tbody>
                                    <!-- GetResponse 360 -->
                                    <tr>
                                        <td>
                                            <input type="submit" name="Submit" class="button-primary"
                                                   value="<?php _e( 'Connect', 'Gr_Integration' ); ?>"/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
							</td>
                            <td style="width: 50%">

                                <?php
                                /**
                                 * Load Rss box.
                                 */
                                gr_load_template( 'admin/settings/partials/rss.php' ); ?>

                                <?php
                                /**
                                 * Load social media box.
                                 */
                                gr_load_template( 'admin/settings/partials/social.php' ); ?>
                            </td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="postbox-container-1" class="postbox-container">

			</div>

		</div>
	</div>
</form>