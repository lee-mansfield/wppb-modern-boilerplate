<?php
/**
 * Requirements unit tests.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);
namespace Modern\Plugin\Tests\Unit;
use Modern\Plugin\Support\Requirements;
use PHPUnit\Framework\TestCase;

/**
 * Tests runtime requirement evaluation.
 */
final class RequirementsTest extends TestCase {

	/**
	 * Verify that sufficiently old minimums pass on the current runtime.
	 *
	 * @return void
	 */
	public function testCurrentRuntimeMeetsOldRequirements(): void {
		$GLOBALS['wp_version'] = '6.7';
		self::assertTrue( ( new Requirements( '7.4', '6.0' ) )->satisfied() );
	}
}
