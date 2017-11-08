<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings form.
 */

$cf7_registration_type = gr_get_option( 'cf7_registration_on' );
?>
<h2 class="nav-tab-wrapper"><?php echo gr_get_admin_tabs( 'gr-integration-contact-form-7' ) ?></h2>

<form method="post" action="<?php echo admin_url( 'admin.php?page=gr-integration-contact-form-7' ); ?>">
    <div class="wrap">

        <div id="poststuff" class="wrap">

            <div id="post-body" class="metabox-holder columns-2">

                <div id="post-body-content">

                    <?php gr_load_template( 'admin/settings/partials/messages.php' ); ?>

                    <table class="widefat fixed">
                        <thead>
                        <tr>
                            <th><b><?php _e( 'Subscribe via Contact From 7', 'Gr_Integration' ); ?></b></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>
                                            <label class="GR_label" for="cf7_registration_integration">
                                                <?php _e( 'Registration integration:', 'Gr_Integration' ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <select class="GR_select2" name="cf7_registration_on" id="cf7_registration_integration">
                                                <option value="0" <?php selected( $cf7_registration_type, 0 ); ?>>
                                                    <?php _e( 'Off', 'Gr_Integration' ); ?>
                                                </option>
                                                <option value="1" <?php selected( $cf7_registration_type, 1 ); ?>>
                                                    <?php _e( 'On', 'Gr_Integration' ); ?>
                                                </option>
                                            </select>

                                            <a class="gr-tooltip">
											<span class="gr-tip">
												<span>
													<?php _e( 'Send new contact to GetResponse.',
                                                        'Gr_Integration' ); ?>
												</span>
											</span>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div id="cf7_registration_show"
                                    <?php if ( '1' !== gr_get_option( 'cf7_registration_on' ) ) : ?>
                                        style="display: none"
                                    <?php endif ?> >

                                    <table>
                                        <tbody>
                                        <tr>
                                            <th>
                                                <label class="GR_label" for="cf7_registration_campaign">
                                                    <?php _e( 'Target Campaign:', 'Gr_Integration' ); ?>
                                                </label>
                                            </th>
                                            <td>
                                                <?php
                                                gr_return_campaign_selector( gr_get_option( 'cf7_registration_campaign' ),
                                                    'cf7_registration_campaign' );
                                                ?>
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
    jQuery('#cf7_registration_integration').change(function () {
        var value = jQuery(this).val();
        if (value === '1') {
            jQuery('#cf7_registration_show').show('slow');
        }
        else {
            jQuery('#cf7_registration_show').hide('slow');
        }
    });
</script>