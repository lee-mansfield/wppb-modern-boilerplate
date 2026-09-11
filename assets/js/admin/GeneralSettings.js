/**
 * GeneralSettings.js : Defines general/default UI controls.
 */
import {
	ToggleControl
} from '@wordpress/components';

export default function generalSettings( {
	enabled,
	onEnabledChange
} ) {
	return (
		<div className="form-controls">
			<ToggleControl
				label="Enable favourites"
				checked={ enabled }
				onChange={ onEnabledChange }
			/>
		</div>
	);
}