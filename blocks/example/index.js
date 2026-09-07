import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import metadata from './block.json';
import './editor.scss';
import './style.scss';

function Edit( { attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<RichText
				tagName="p"
				value={ attributes.message }
				onChange={ ( message ) => setAttributes( { message } ) }
			/>
		</div>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null,
} );
