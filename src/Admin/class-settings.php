<?php
/**
 * Plugin register settings class.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Admin;

use Modern\Plugin\Contracts\Service;

/**
 * Registers and renders the plugin settings page.
 */
final class Settings implements Service {

	public const OPTION_NAME = 'modern_plugin_settings';

	/**
	 * Register settings-related hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register the plugin setting and its fields.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting(
			'modern_plugin',
			self::OPTION_NAME,
			array(
				'type'              => 'object',
				'default'           => array( 'message' => '' ),
				'sanitize_callback' => static fn ( array $value ): array => array( 'message' => sanitize_text_field( $value['message'] ?? '' ) ),
			)
		);
		add_settings_section( 'modern_main', __( 'General', 'modern-plugin' ), '__return_false', 'modern-plugin' );
		add_settings_field( 'message', __( 'Message', 'modern-plugin' ), array( $this, 'render_message' ), 'modern-plugin', 'modern_main' );
	}

	/**
	 * Render the message setting field.
	 *
	 * @return void
	 */
	public function render_message(): void {
		$options = get_option( self::OPTION_NAME, array( 'message' => '' ) );
		printf( '<input class="regular-text" name="%s[message]" value="%s">', esc_attr( self::OPTION_NAME ), esc_attr( $options['message'] ?? '' ) );
	}

	/**
	 * Read the shared message, using the caller's fallback when blank.
	 *
	 * @param string $fallback Message used when no setting has been saved.
	 * @return string Saved message or fallback.
	 */
	public static function get_message( string $fallback ): string {
		$options = get_option( self::OPTION_NAME, array() );
		$message = is_array( $options ) ? ( $options['message'] ?? '' ) : '';

		return is_string( $message ) && '' !== trim( $message ) ? $message : $fallback;
	}
}
