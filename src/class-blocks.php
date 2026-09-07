<?php
/**
 * Dynamic block registration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Contracts\Service;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Discovers and registers compiled blocks.
 */
final class Blocks implements Service {

	/**
	 * Register block hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'registerBlocks' ) );
	}

	/**
	 * Register every compiled block that contains block metadata.
	 *
	 * @return void
	 */
	public function registerBlocks(): void {
		$blocks_path = PATH . 'build/blocks';
		if ( ! is_dir( $blocks_path ) ) {
			return;
		}

		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $blocks_path ) );
		foreach ( $iterator as $file ) {
			if ( $file->getFilename() === 'block.json' ) {
				register_block_type( $file->getPath() );
			}
		}
	}
}
