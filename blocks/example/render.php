<?php
/**
 * Render the example block using the shared front-end partial.
 *
 * @var array{message?: string} $attributes Block attributes.
 * @package ModernPlugin
 */

defined( 'ABSPATH' ) || exit;

$message = \Modern\Plugin\Settings\SettingsPage::getMessage(
	$attributes['message'] ?? __( 'Hello from Modern Plugin.', 'modern-plugin' )
);
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core returns escaped attributes. ?>>
	<?php require \Modern\Plugin\PATH . 'templates/frontend/example.php'; ?>
</div>
