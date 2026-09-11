<?php
/**
 * Plugin register react settings class.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Admin;

use Modern\Plugin\Contracts\Service;

/**
 * Registers and renders the plugin settings page.
 */
final class React_Settings implements Service {

	public const OPTION_NAME = 'modern_plugin_react_settings';

	/**
	 * Register settings-related hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'rest_api_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register the plugin setting and its fields.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting(
			'modern-plugin',
			self::OPTION_NAME,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => array(
					'enabled'  => false,
					'label'    => 'Add to favourites',
					'position' => 'after',
				),
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => array(
							'enabled'  => array(
								'type' => 'boolean',
							),
							'label'    => array(
								'type' => 'string',
							),
							'position' => array(
								'type' => 'string',
							),
						),
					),
				),
			)
		);
	}

	/**
	 * Sanitize the modern_plugin_react_settings object.
	 *
	 * @param array<string, mixed> $settings Array of settings to sanitize.
	 * @return array{enabled: bool, label: string, position: 'before'|'after'} Sanitized settings.
	 */
	public function sanitize_settings( array $settings ): array {
		return array(
			'enabled'  => ! empty( $settings['enabled'] ),
			'label'    => sanitize_text_field( $settings['label'] ?? '' ),
			'position' => in_array(
				$settings['position'] ?? '',
				array( 'before', 'after' ),
				true
			)
				? $settings['position']
				: 'after',
		);
	}
}
