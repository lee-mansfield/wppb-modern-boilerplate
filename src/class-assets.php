<?php
/**
 * Front-end asset registration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Contracts\Service;

/**
 * Enqueues the plugin's front-end assets.
 */
final class Assets implements Service {

	/**
	 * Register asset hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
	}

	/**
	 * Enqueue the front-end script and stylesheet.
	 *
	 * @return void
	 */
	public function enqueue_frontend(): void {
		$asset_file = PATH . 'build/frontend.asset.php';
		$asset      = file_exists( $asset_file ) ? require $asset_file : array(
			'dependencies' => array(),
			'version'      => VERSION,
		);

		wp_enqueue_script( 'modern-plugin', plugin_url() . 'build/frontend.js', $asset['dependencies'], $asset['version'], true );
		wp_enqueue_style( 'modern-plugin', plugin_url() . 'build/frontend.css', array(), $asset['version'] );
	}
}
