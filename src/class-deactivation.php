<?php
/**
 * Plugin deactivation handling.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

/**
 * Runs tasks required when the plugin is deactivated.
 */
final class Deactivation {

	/**
	 * Refresh rewrite rules on deactivation.
	 *
	 * @return void
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}
}
