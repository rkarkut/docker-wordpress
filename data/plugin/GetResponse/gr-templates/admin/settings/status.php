<?php

defined( 'ABSPATH' ) || exit;

$apiKey = gr_get_option( 'api_key' );
$apiKey = strlen($apiKey) > 0 ? str_repeat("*", strlen($apiKey) - 6) . substr($apiKey, -6) : '';

?>

<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-status' ) ?></h2>

<div id="poststuff" class="wrap">

	<div id="post-body" class="metabox-holder columns-2">

		<div id="post-body-content">

			<?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

			<table class="widefat fixed">
				<thead>
				<tr>
					<th><b><?php _e( 'Account info', 'Gr_Integration' ); ?></b></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<!-- API Key -->
				<tr valign="top">
					<td style="width: 50%; vertical-align: top">

						<table>
							<tbody>
							<!-- API Key -->
							<tr valign="top">
								<th scope="row" style="width: 50%">
									<label>
										<?php _e( 'Status', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<span class="connected-status"><?php _e( 'CONNECTED', 'Gr_Integration' ); ?></span>
									<small>(<a
											href="admin.php?page=gr-integration-status&action=disconnect"><?php _e( 'disconnect',
												'Gr_Integration' ) ?></a>)
									</small>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>
										<?php _e( 'API Key', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo $apiKey ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'GetResponse 360', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php if ( '1' === gr_get_option( 'getresponse_360_account' ) ) {
											_e( 'Yes', 'Gr_Integration' );
										}
										else {
											_e( 'No', 'Gr_Integration' );
										}
									?>
								</td>
							</tr>

							<?php if (null!== gr_get_option('account_first_name')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'First name', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_first_name') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null!== gr_get_option('account_last_name')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'Last name', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_last_name') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null!== gr_get_option('account_company_name')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'Company', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_company_name') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null!== gr_get_option('account_email')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'Email address', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_email') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null!== gr_get_option('account_country_name')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'Country', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_country_name') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null!== gr_get_option('account_state')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'State', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
                                    <?php echo gr_get_option('account_state') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null !== gr_get_option('account_zip_code')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'ZIP/Postal Code', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_zip_code') ?>
								</td>
							</tr>

							<?php endif ?>

							<?php if (null !== gr_get_option('account_street')) :?>

							<tr valign="top">
								<th scope="row">
									<label>
										<?php _e( 'Street', 'Gr_Integration' ); ?>:
									</label>
								</th>
								<td>
									<?php echo gr_get_option('account_street') ?>
								</td>
							</tr>

							<?php endif ?>

                            <?php if (null !== gr_get_option('account_city')) :?>

                                <tr valign="top">
                                    <th scope="row">
                                        <label>
                                            <?php _e( 'City', 'Gr_Integration' ); ?>:
                                        </label>
                                    </th>
                                    <td>
                                        <?php echo gr_get_option('account_city') ?>
                                    </td>
                                </tr>

                            <?php endif ?>

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
