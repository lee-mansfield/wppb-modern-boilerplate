<?php
/**
 * Admin settings page partial, loaded after the service checks permissions.
 *
 * @package ModernPlugin
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'modern_plugin' );
		do_settings_sections( 'modern-plugin' );
		submit_button();
		?>
	</form>
</div>
