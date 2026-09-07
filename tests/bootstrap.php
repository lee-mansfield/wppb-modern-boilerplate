<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);
require dirname( __DIR__ ) . '/vendor/autoload.php';
Brain\Monkey\setUp();
register_shutdown_function( static fn () => Brain\Monkey\tearDown() );
