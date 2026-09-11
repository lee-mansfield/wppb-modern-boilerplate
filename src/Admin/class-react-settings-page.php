<?php
/**
 * Plugin React based settings page.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Admin;

use Modern\Plugin\Contracts\Service;

/**
 * Registers and renders the plugin settings page.
 */
final class React_Settings_Page implements Service {

	/**
	 * Set the plugin suffix.
	 *
	 * @var string
	 */
	private string $hook_suffix = '';

	/**
	 * Register settings-related hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ), 10, 1 );
	}

	/**
	 * Add the plugin page to the Settings menu.
	 *
	 * @return void
	 */
	public function add_page(): void {
		$this->hook_suffix    = add_menu_page(
			__( 'Modern Plugin (React UI)', 'modern-plugin' ),
			__( 'Modern Plugin (React UI)', 'modern-plugin' ),
			'manage_options',
			'modern-plugin-react',
			array( $this, 'render_template' ),
			'dashicons-admin-plugins'
		);
		$settings_hook_suffix = add_options_page(
			__( 'Modern Plugin (React UI)', 'modern-plugin' ),
			__( 'Modern Plugin (React UI)', 'modern-plugin' ),
			'manage_options',
			'modern-plugin-react',
			array( $this, 'render_template' ),
		);

		if ( false !== $settings_hook_suffix ) {
			$this->hook_suffix = $settings_hook_suffix;
		}
	}

	/**
	 * Enqueue assets for this class.
	 *
	 * @param string $hook_suffix Name of the plugin.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( $hook_suffix !== $this->hook_suffix ) {
			return;
		}

		$asset_file = require \Modern\Plugin\PATH . 'build/admin/index.asset.php';

		wp_enqueue_script(
			'my-plugin-admin',
			\Modern\Plugin\plugin_url() . 'build/admin/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);
		wp_enqueue_style(
			'my-plugin-admin',
			\Modern\Plugin\plugin_url() . 'build/admin/index.css',
			array( 'wp-components' ),
			$asset_file['version']
		);
	}

	/**
	 * Render the settings page for authorized users.
	 *
	 * @return void
	 */
	public function render_template(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		require \Modern\Plugin\PATH . 'templates/admin/tpl-react-settings.php';
	}
}
