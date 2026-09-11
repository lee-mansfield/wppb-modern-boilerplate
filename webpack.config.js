const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		frontend: path.resolve(process.cwd(), 'assets/js/frontend.js'),
        'admin/index': path.resolve(process.cwd(), 'assets/js/admin/index.js'),
		'blocks/example/index': path.resolve(process.cwd(), 'blocks/example/index.js'),
	},
	plugins: [
		...defaultConfig.plugins,
		new CopyWebpackPlugin({ patterns: [
			{ from: '*/block.json', context: path.resolve(process.cwd(), 'blocks'), to: 'blocks/[path][name][ext]' },
			{ from: '*/render.php', context: path.resolve(process.cwd(), 'blocks'), to: 'blocks/[path][name][ext]' },
		] }),
	],
};
