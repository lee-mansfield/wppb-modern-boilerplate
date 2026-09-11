<?php
/**
 * Example REST API route.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Rest;

use Modern\Plugin\Contracts\Service;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Registers and serves the example status endpoint.
 */
final class Example_Route implements Service {

	/**
	 * Register REST API hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the example status route.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			'modern-plugin/v1',
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'status' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Return the plugin's current status.
	 *
	 * @param WP_REST_Request $request REST API request object.
	 * @return WP_REST_Response Status response.
	 */
	public function status( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'ok'      => true,
				'version' => \Modern\Plugin\VERSION,
			)
		);
	}
}
