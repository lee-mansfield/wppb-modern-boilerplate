<?php
/**
 * Plugin Name:       Modern Plugin Boilerplate
 * Plugin URI:        https://example.com/
 * Description:       A modern, reusable foundation for WordPress plugins.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      8.1
 * Author:            Your Name
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       modern-plugin
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Support\Requirements;

defined( 'ABSPATH' ) || exit;

const VERSION = '1.0.0';
const FILE    = __FILE__;
const PATH    = __DIR__ . '/';

/**
 * Get the plugin's base URL.
 *
 * @return string Plugin base URL with a trailing slash.
 */
function plugin_url(): string {
	return plugin_dir_url( FILE );
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function (): void {
			echo '<div class="notice notice-error"><p>';
			echo esc_html__( 'Modern Plugin requires its Composer dependencies. Run composer install.', 'modern-plugin' );
			echo '</p></div>';
		}
	);
	return;
}

require __DIR__ . '/vendor/autoload.php';

register_activation_hook( __FILE__, array( Activation::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Deactivation::class, 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function (): void {
		$requirements = new Requirements( '8.1', '6.7' );
		if ( ! $requirements->satisfied() ) {
			$requirements->render_notice();
			return;
		}

		( new Plugin() )->register();
	}
);
