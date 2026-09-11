/**
 * index.js : App bootstrap.
 */
import '../../scss/admin/admin.scss';
import { createRoot } from '@wordpress/element';
import App from './App';

const container = document.getElementById( 'my-plugin-admin' );

if ( container ) {
	const root = createRoot( container );

	root.render( <App /> );
}
