/**
 * AppearanceSettings.js : Defines appearance UI controls.
 */
import {
	TextControl,
	SelectControl,
} from '@wordpress/components';

export default function generalSettings( {
	label,
	position,
	onLabelChange,
	onPositionChange
} ) {
	return (
		<div className="form-controls">
			<TextControl
				label="Button label"
				value={ label }
				onChange={ onLabelChange }
			/>

			<SelectControl
				label="Display position"
				value={ position }
				options={ [
					{ label: 'Before content', value: 'before' },
					{ label: 'After content', value: 'after' },
				] }
				onChange={ onPositionChange }
			/>
		</div>
	);
}