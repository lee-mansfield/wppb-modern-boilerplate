<?php
/**
 * Example taxonomy registration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Content;

use Modern\Plugin\Contracts\Service;

/**
 * Registers the example topic taxonomy.
 */
final class Example_Taxonomy implements Service {

	public const TAXONOMY = 'modern_topic';

	/**
	 * Register the taxonomy hook.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Register the topic taxonomy with WordPress.
	 *
	 * @return void
	 */
	public function register_taxonomy(): void {
		register_taxonomy(
			self::TAXONOMY,
			array( Example_Post_Type::POST_TYPE ),
			array(
				'labels'       => array(
					'name'          => __( 'Topics', 'modern-plugin' ),
					'singular_name' => __( 'Topic', 'modern-plugin' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'hierarchical' => true,
				'rewrite'      => array( 'slug' => 'item-topic' ),
			)
		);
	}
}
