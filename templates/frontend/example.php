<?php
/**
 * Shared front-end partial for the action hook, shortcode and block examples.
 *
 * @package ModernPlugin
 * @var string $message Message prepared by the service or block renderer.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="modern-plugin-example">
	<h2><?php esc_html_e( 'Plugin template example', 'modern-plugin' ); ?></h2>
	<p><?php echo wp_kses_post( $message ); ?></p>
</section>
