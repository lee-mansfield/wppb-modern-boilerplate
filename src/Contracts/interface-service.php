<?php
/**
 * Service contract.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Contracts;

/**
 * Defines a component that can register itself with WordPress.
 */
interface Service {

	/**
	 * Register the service's WordPress hooks and functionality.
	 *
	 * @return void
	 */
	public function register(): void;
}
