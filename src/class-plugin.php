<?php
/**
 * Main plugin orchestration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Content\ExamplePostType;
use Modern\Plugin\Content\ExampleTaxonomy;
use Modern\Plugin\Contracts\Service;
use Modern\Plugin\Frontend\ExampleDisplay;
use Modern\Plugin\Rest\ExampleRoute;
use Modern\Plugin\Settings\SettingsPage;

/**
 * Registers the plugin's services.
 */
final class Plugin {

	/**
	 * Service classes registered during plugin bootstrap.
	 *
	 * @var list<class-string<Service>>
	 */
	private array $services = array(
		Assets::class,
		Blocks::class,
		ExamplePostType::class,
		ExampleTaxonomy::class,
		ExampleRoute::class,
		SettingsPage::class,
		ExampleDisplay::class,
	);

	/**
	 * Instantiate and register each plugin service.
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->services as $service ) {
			( new $service() )->register();
		}
	}
}
