<?php
/**
 * Plugin activation handling.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Content\ExamplePostType;
use Modern\Plugin\Content\ExampleTaxonomy;

/**
 * Runs tasks required when the plugin is activated.
 */
final class Activation {

	/**
	 * Register rewrite-dependent content and refresh rewrite rules.
	 *
	 * @return void
	 */
	public static function activate(): void {
		( new ExamplePostType() )->registerPostType();
		( new ExampleTaxonomy() )->registerTaxonomy();
		flush_rewrite_rules();
	}
}
