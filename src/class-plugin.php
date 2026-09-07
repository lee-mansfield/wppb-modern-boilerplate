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
 * Checks the github repo for any code updates periodically.
 */
require dirname( __DIR__, 1 ) . '/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$set_repo_url = defined( 'MAXIMUM_OVERDRIVE_PLUGIN_GIT_URL' )
				? constant( 'MAXIMUM_OVERDRIVE_PLUGIN_GIT_URL' )
				: '';

$set_plugin_slug = defined( 'MAXIMUM_OVERDRIVE_PLUGIN_SLUG' )
					? constant( 'MAXIMUM_OVERDRIVE_PLUGIN_SLUG' )
					: '';

if (
	! empty( $set_repo_url )
	&& ! empty( $set_plugin_slug )
) {
	$mo_plugin_update_checker = PucFactory::buildUpdateChecker(
		$set_repo_url,
		__FILE__,
		$set_plugin_slug
	);

	// Set the branch that contains the stable release.
	$mo_plugin_update_checker->setBranch( 'main' );

	// Optional: If you're using a private repository, specify the access token like this.
	$set_token = defined( 'MAXIMUM_OVERDRIVE_PLUGIN_RELEASE_TOKEN' )
					? constant( 'MAXIMUM_OVERDRIVE_PLUGIN_RELEASE_TOKEN' )
					: '';
	$mo_plugin_update_checker->setAuthentication( $set_token );
}

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
