<?php
/**
 * Example post type registration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin\Content;

use Modern\Plugin\Contracts\Service;

/**
 * Registers the example item post type.
 */
final class Example_Post_Type implements Service {

	public const POST_TYPE = 'modern_item';

	/**
	 * Register the post type hook.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register the item post type with WordPress.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => __( 'Items', 'modern-plugin' ),
					'singular_name' => __( 'Item', 'modern-plugin' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'items' ),
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'menu_icon'    => 'dashicons-portfolio',
			)
		);
	}
}
