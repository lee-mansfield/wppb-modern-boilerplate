<?php
/**
 * Plugin uninstall handler.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Opt in to destructive cleanup per project. Keep user data by default.
// delete_option( 'modern_plugin_settings' );.
