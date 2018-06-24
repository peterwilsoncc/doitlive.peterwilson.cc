const glob = require('glob');
const { prodConfig, rootRelative, loaderRules } = require( './webpack-shared-config' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );

// This is the production configuration, focused on optimized builds.
// The development configuration lives in a separate file.
// We export an array of configuration objects to support separate, parallel
// builds for different parts of the project.
module.exports = [
	// Gutenberg Blocks.
	prodConfig( {
		// Resolve entry and output paths relative to this directory.
		context: rootRelative( 'themes/pwcc-003/assets' ),
		entry: {
			'app': './src/app.js'
		},
 			output: {
			filename: './build/[name].js',
		},
		plugins: [
			// Extract styles into a standalone CSS file.
			new ExtractTextPlugin( './build/[name].css' ),
		],
	} ),
];
