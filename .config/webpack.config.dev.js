const autoprefixer = require( 'autoprefixer' );
const glob = require('glob');
const path = require( 'path' );
const webpack = require( 'webpack' );
const fs = require( 'fs' );
const ManifestPlugin = require( 'webpack-manifest-plugin' );

const { devConfig, rootRelative } = require( './webpack-shared-config' );

// This is the development configuration, focused on developer experience and
// fast rebuilds. The production configuration lives in a separate file.
// We export an array of configuration objects to support separate, parallel
// builds for different parts of the project.
module.exports = [
	// Theme assets.
	devConfig( {
		// Resolve entry and output paths relative to this directory.
		context: rootRelative( 'themes/pwcc-003/assets/' ),
		entry: {
			'app': './src/app.js'
		},
		output: {
			// The URL of output.path (set to the provided context folder):
			publicPath: 'http://localhost:8080/themes/pwcc-003/assets/',
			filename: 'build/[name].js',
		},
	} ),
];
