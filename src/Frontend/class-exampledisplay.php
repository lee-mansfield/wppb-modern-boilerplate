<?php
/**
 * Front-end action hook and shortcode example.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Frontend;

use Modern\Plugin\Contracts\Service;
use Modern\Plugin\Settings\SettingsPage;

/**
 * Displays the same PHP partial through an action or a shortcode.
 */
final class ExampleDisplay implements Service {

	/**
	 * Register the display callback and shortcode.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'modern_plugin_example', array( $this, 'render' ) );
		add_shortcode( 'modern_plugin_example', array( $this, 'renderShortcode' ) );
	}

	/**
	 * Output the partial at the point where the action is fired.
	 *
	 * @return void
	 */
	public function render(): void {
		$message = SettingsPage::getMessage( __( 'Hello from the plugin front-end template!', 'modern-plugin' ) );

		require \Modern\Plugin\PATH . 'templates/frontend/example.php';
	}

	/**
	 * Return the partial HTML for insertion into page content.
	 *
	 * @return string Rendered HTML.
	 */
	public function renderShortcode(): string {
		ob_start();

		try {
			$this->render();
			return (string) ob_get_contents();
		} finally {
			ob_end_clean();
		}
	}
}
