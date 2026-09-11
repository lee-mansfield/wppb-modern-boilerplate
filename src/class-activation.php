<?php
/**
 * Plugin activation handling.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Content\Example_Post_Type;
use Modern\Plugin\Content\Example_Taxonomy;

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
		( new Example_Post_Type() )->register_post_type();
		( new Example_Taxonomy() )->register_taxonomy();
		flush_rewrite_rules();
	}
}
