<?php

defined( 'ABSPATH' ) || exit;

/**
 * Display API Key error message.
 */

?>

<?php if ( false !== gr()->error_messages ): ?>
	<?php foreach ( gr()->error_messages as $message ) : ?>
		<div id="message" class="error" style="margin: 1px 0 15px">
			<p>
				<strong>
					<?php _e( 'Settings error', 'Gr_Integration' ); ?>
				</strong> - <?php _e($message, 'Gr_Integration' ) ?>
			</p>
		</div>
	<?php endforeach ?>
<?php endif ?>

<?php if ( false !== gr()->success_messages ): ?>
	<?php foreach ( gr()->success_messages as $message ) : ?>
		<div id="message" class="updated fade" style="margin: 1px 0 15px">
			<p><strong>
				<?php _e( $message, 'Gr_Integration' ) ?>.
			</p></strong>
		</div>
	<?php endforeach ?>
<?php endif ?>

