<?php
/**
 * Main plugin orchestration.
 *
 * @package ModernPlugin
 */

declare(strict_types=1);

namespace Modern\Plugin;

use Modern\Plugin\Content\Example_Post_Type;
use Modern\Plugin\Content\Example_Taxonomy;
use Modern\Plugin\Contracts\Service;
use Modern\Plugin\Frontend\Example_Display;
use Modern\Plugin\Rest\Example_Route;
use Modern\Plugin\Admin\Settings;
use Modern\Plugin\Admin\React_Settings;
use Modern\Plugin\Admin\Settings_Page;
use Modern\Plugin\Admin\React_Settings_Page;

/**
 * Checks the github repo for any code updates periodically.
 */
require dirname( __DIR__, 1 ) . '/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p7\Vcs\BaseChecker as Repository_Update_Checker;

$set_repo_url = defined( 'WPPB_CI_CD_PLUGIN_GIT_URL' )
				? constant( 'WPPB_CI_CD_PLUGIN_GIT_URL' )
				: '';

$set_plugin_slug = defined( 'WPPB_CI_CD_PLUGIN_SLUG' )
					? constant( 'WPPB_CI_CD_PLUGIN_SLUG' )
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

	if ( $mo_plugin_update_checker instanceof Repository_Update_Checker ) {
		// Set the branch that contains the stable release.
		$mo_plugin_update_checker->setBranch( 'main' );

		// Optional: If you're using a private repository, specify the access token like this.
		$set_token = defined( 'WPPB_CI_CD_PLUGIN_GIT_RELEASE_TOKEN' )
						? constant( 'WPPB_CI_CD_PLUGIN_GIT_RELEASE_TOKEN' )
						: '';
		$mo_plugin_update_checker->setAuthentication( $set_token );
	}
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
		Example_Post_Type::class,
		Example_Taxonomy::class,
		Example_Route::class,
		Settings::class,
		React_Settings::class,
		Settings_Page::class,
		React_Settings_Page::class,
		Example_Display::class,
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
