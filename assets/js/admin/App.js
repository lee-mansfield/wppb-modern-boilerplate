/**
 * App.js : Main entry point for the App.
 */
import { Button, Notice, Spinner, TabPanel } from '@wordpress/components';

import GeneralSettings from './GeneralSettings.js';
import AppearanceSettings from './AppearanceSettings';
import useSettings from './useSettings';

export default function App() {
	const {
		settings,
		updateSetting,
		loadSettings,
		saveSettings,
		isLoading,
		isSaving,
		hasChanged,
		loadError,
		notice,
		dismissNotice,
	} = useSettings();

	if ( isLoading ) {
		return (
			<div role="status">
				<Spinner />
				<span>Loading settings…</span>
			</div>
		);
	}

	if ( loadError ) {
		return (
			<div>
				<Notice status="error" isDismissible={ false }>
					{ loadError }
				</Notice>

				<Button variant="secondary" onClick={ loadSettings }>
					Try again
				</Button>
			</div>
		);
	}

	return (
		<div>
			{ notice && (
				<Notice status={ notice.status } onRemove={ dismissNotice }>
					{ notice.message }
				</Notice>
			) }

			<h1>Modern Plugin</h1>

			<p>
				Example React based control panel for saving state using both
				PHP and WordPress/React.
			</p>

			<h2>Settings</h2>

			<TabPanel
				initialTabName="general"
				tabs={ [
					{
						name: 'general',
						title: 'General',
					},
					{
						name: 'appearance',
						title: 'Appearance',
					},
				] }
			>
				{ ( tab ) => (
					<fieldset disabled={ isSaving }>
						
						<legend className="screen-reader-text">
							{ tab.title } settings
						</legend>

						{ tab.name === 'general' && (
							<GeneralSettings
								enabled={ settings.enabled }
								onEnabledChange={ ( value ) =>
									updateSetting( 'enabled', value )
								}
							/>
						)}

						{ tab.name === 'appearance' && (
							<AppearanceSettings
								label={ settings.label }
								position={ settings.position }
								onLabelChange={ ( value ) =>
									updateSetting( 'label', value )
								}
								onPositionChange={ ( value ) =>
									updateSetting( 'position', value )
								}
							/>
						) }
					</fieldset>
				) }
			</TabPanel>

			<Button
				className="align-right"
				variant="primary"
				onClick={ saveSettings }
				disabled={ isSaving || ! hasChanged }
			>
				{ isSaving ? 'Saving…' : 'Save settings' }
			</Button>
		</div>
	);
}
