<?php
/**
 * Plugin settings page.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Settings;

use Modern\Plugin\Contracts\Service;

/**
 * Registers and renders the plugin settings page.
 */
final class SettingsPage implements Service {

	private const OPTION = 'modern_plugin_settings';

	/**
	 * Read the shared message, using the caller's fallback when blank.
	 *
	 * @param string $fallback Message used when no setting has been saved.
	 * @return string Saved message or fallback.
	 */
	public static function getMessage( string $fallback ): string {
		$options = get_option( self::OPTION, array() );
		$message = is_array( $options ) ? ( $options['message'] ?? '' ) : '';

		return is_string( $message ) && '' !== trim( $message ) ? $message : $fallback;
	}

	/**
	 * Register settings-related hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'addPage' ) );
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
	}

	/**
	 * Add the plugin page to the Settings menu.
	 *
	 * @return void
	 */
	public function addPage(): void {
		add_options_page( __( 'Modern Plugin', 'modern-plugin' ), __( 'Modern Plugin', 'modern-plugin' ), 'manage_options', 'modern-plugin', array( $this, 'render' ) );
	}

	/**
	 * Register the plugin setting and its fields.
	 *
	 * @return void
	 */
	public function registerSettings(): void {
		register_setting(
			'modern_plugin',
			self::OPTION,
			array(
				'type'              => 'object',
				'default'           => array( 'message' => '' ),
				'sanitize_callback' => static fn ( array $value ): array => array( 'message' => sanitize_text_field( $value['message'] ?? '' ) ),
			)
		);
		add_settings_section( 'modern_main', __( 'General', 'modern-plugin' ), '__return_false', 'modern-plugin' );
		add_settings_field( 'message', __( 'Message', 'modern-plugin' ), array( $this, 'renderMessage' ), 'modern-plugin', 'modern_main' );
	}

	/**
	 * Render the message setting field.
	 *
	 * @return void
	 */
	public function renderMessage(): void {
		$options = get_option( self::OPTION, array( 'message' => '' ) );
		printf( '<input class="regular-text" name="%s[message]" value="%s">', esc_attr( self::OPTION ), esc_attr( $options['message'] ?? '' ) );
	}

	/**
	 * Render the settings page for authorized users.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		require \Modern\Plugin\PATH . 'templates/admin/settings.php';
	}
}
