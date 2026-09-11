/**
 * useSettings.js : Governs the state, loading and saving of settings.
 */
import { useCallback, useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export default function useSettings() {
	const [ settings, setSettings ] = useState( {
		enabled: false,
		label: 'Add to favourites',
		position: 'after',
	} );

	const [ isLoading, setIsLoading ] = useState( true );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ loadError, setLoadError ] = useState( null );
	const [ notice, setNotice ] = useState( null );
	const [ hasChanged, setHasChanged ] = useState( false );

	const loadSettings = useCallback( async () => {
		setIsLoading( true );
		setLoadError( null );

		try {
			const response = await apiFetch( {
				path: '/wp/v2/settings',
			} );

			const savedSettings = response.modern_plugin_react_settings;

			if ( ! savedSettings ) {
				throw new Error( 'Settings were missing from the response.' );
			}

			setSettings( savedSettings );
		} catch {
			setLoadError( 'Settings could not be loaded. Please try again.' );
		} finally {
			setIsLoading( false );
		}
	}, [] );

	useEffect( () => {
		loadSettings();
	}, [ loadSettings ] );

	function updateSetting( name, value ) {
		setSettings( ( current ) => ( {
			...current,
			[ name ]: value,
		} ) );

		setHasChanged( true );
	}

	async function saveSettings() {
		if ( isLoading || isSaving || loadError ) {
			return;
		}

		setIsSaving( true );
		setNotice( null );

		try {
			const response = await apiFetch( {
				path: '/wp/v2/settings',
				method: 'POST',
				data: {
					modern_plugin_react_settings: settings,
				},
			} );

			setSettings( response.modern_plugin_react_settings );

			setNotice( {
				status: 'success',
				message: 'Settings saved.',
			} );
			
			setHasChanged( false );
		} catch {
			setNotice( {
				status: 'error',
				message: 'Settings could not be saved. Please try again.',
			} );
		} finally {
			setIsSaving( false );
		}
	}

	return {
		settings,
		updateSetting,
		loadSettings,
		saveSettings,
		isLoading,
		isSaving,
		hasChanged,
		loadError,
		notice,
		dismissNotice: () => setNotice( null ),
	};
}