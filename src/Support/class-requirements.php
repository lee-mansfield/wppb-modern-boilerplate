<?php
/**
 * Runtime requirement checks.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Support;

/**
 * Checks the server and WordPress versions required by the plugin.
 */
final class Requirements {

	/**
	 * Create a requirements checker.
	 *
	 * @param string $php       Minimum PHP version.
	 * @param string $wordpress Minimum WordPress version.
	 */
	public function __construct( private string $php, private string $wordpress ) {}

	/**
	 * Determine whether the current runtime meets all requirements.
	 *
	 * @return bool True when all requirements are satisfied.
	 */
	public function satisfied(): bool {
		global $wp_version;
		return version_compare( PHP_VERSION, $this->php, '>=' ) && version_compare( $wp_version, $this->wordpress, '>=' );
	}

	/**
	 * Register an admin notice describing unmet requirements.
	 *
	 * @return void
	 */
	public function render_notice(): void {
		add_action(
			'admin_notices',
			function (): void {
				printf(
					'<div class="notice notice-error"><p>%s</p></div>',
					esc_html( sprintf( 'Modern Plugin requires PHP %s and WordPress %s or newer.', $this->php, $this->wordpress ) )
				);
			}
		);
	}
}
