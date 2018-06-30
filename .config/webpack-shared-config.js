const autoprefixer = require( 'autoprefixer' );
const fs = require( 'fs' );
const path = require( 'path' );
const webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const ManifestPlugin = require( 'webpack-manifest-plugin' );
const UglifyJSPlugin = require( 'uglifyjs-webpack-plugin' );

const env = require( './env' );

// Helper for use in config files.
const rootRelative = ( relPath ) => path.resolve( process.cwd(), relPath );

/**
 * Map entries in config to their path in the hot reloader server.
 *
 * The expected entry value in dev should be formatted like this:
 *     entry: {
 *         'themes/[theme-name]/assets/build/[type]': [
 *             'webpack-dev-server/client?http://localhost:8080',
 *             'webpack/hot/dev-server',
 *             '.themes/[theme-name]/assets/src/[type]/index.js'
 *         ],
 *         [ ... ]
 *     }
 *
 * @param {Object} entries Defined entrypoints for the dev server to handle, in the format [scriptHandle]: entryPath
 * @return {Object} Entries, with the value for each asset as an array with the webpack-dev-server and webpack-hot inserted before the asset URL.
 */
const mapEntriesToHotReloader = ( entries ) => {
	for ( key in entries ) {
		entries[ key ] = [
			require.resolve('webpack-dev-server/client') + '?http://localhost:8080/',
			require.resolve('webpack/hot/dev-server'),
			...( Array.isArray( entries[ key ] ) ? entries[ key ] : [ entries[ key ] ] ),
		];
	}

	return entries;
}

// Build a list of dynamically-generated asset files, then clean them up when
// the development server exits. PHP will use these files to detect and load
// from the Webpack development server when it is running.
const assetFiles = [];

const manifestFile = ( context ) => {
	assetFiles.push( `${ context }/asset-manifest.json` );

	return new ManifestPlugin( {
		fileName: 'asset-manifest.json',
		writeToFileEmit: true,
	} );
};

[ 'SIGINT', 'SIGTERM' ].forEach( ( sig ) => {
	process.on( sig, () => {
		// Remove the asset manifest file on server termination
		assetFiles.forEach( ( file ) => fs.unlinkSync( file ) );
		process.exit();
	} );
} );

const loaderRules = {
	js: {
		test: /\.jsx?$/,
		exclude: path.resolve( __dirname, '/node_modules/' ),
		loader: require.resolve( 'babel-loader' ),
		options: {
			// This is a feature of `babel-loader` for webpack (not Babel itself).
			// It enables caching results in ./node_modules/.cache/babel-loader/
			// directory for faster rebuilds.
			cacheDirectory: true,
		},
	},
	// Specify the SCSS rules as an array of loaders which will apply in both
	// development and production builds, then let the configuration choose
	// whether to use them with style-loader or ExtractTextPlugin.
	scss: [
		{
			loader: require.resolve( 'css-loader' ),
			options: {
				importLoaders: 1,
				sourceMap: true,
				minimize: true,
			},
		},
		{
			loader: require.resolve( 'clean-css-loader' ),
			options: {
				level: 2,
				inline: ["remote"],
			},
		},
		{
			loader: require.resolve( 'postcss-loader' ),
			options: {
				ident: 'postcss',
				plugins: () => [
					require( 'postcss-flexbugs-fixes' ),
					autoprefixer( {
						flexbox: 'no-2009',
					} ),
				],
				sourceMap: true,
			},
		},
		{
			loader: require.resolve( 'sass-loader' ),
			options: {
				sourceMap: true,
			},
		},
	],
	staticAssets: {
		test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|eot|ttf)$/,
		loader: require.resolve( 'url-loader' ),
		query: {
			name: '[name].[ext]',
			limit: 10000,
		},
	},
};

/**
 * Generate a new development Webpack configuration object.
 *
 * The provided configuration object is assumed to specify these keys:
 *
 * - context
 * - entry
 * - output.publicPath
 * - output.fileName
 *
 * @param {Object} config A Webpack configuration object to augment.
 * @return {Object} A Webpack configuration object.
 */
const devConfig = ( config ) => {
	// Remove dev-mode assets manifest when server quits.
	return {
		mode: 'development',

		devtool: 'cheap-module-source-map',

		module: {
			strictExportPresence: true,

			rules: [
				loaderRules.js,
				{
					test: /\.s?css$/,
					use: [
						{ loader: require.resolve( 'style-loader' ) },
						...loaderRules.scss,
					],
				},
				loaderRules.staticAssets,
			],
		},

		// Permit the provided config to override any of the above.
		...config,

		// Configure the devServer.
		// TODO: investigate and resolve why we are seeing the console warning
		// "Error: sourceMapURL could not be parsed" when using the hot dev server.
		devServer: {
			hot: true,
			headers: {
				'Access-Control-Allow-Origin': '*',
			},
		},

		// Enable hot reloading.
		entry: mapEntriesToHotReloader( config.entry ),

		// Set some useful output options.
		output: {
			// Add /* filename */ comments to generated require()s in the output.
			pathinfo: true,

			// Assume that output paths should be relative to the provided context.
			path: config.context,

			// Point sourcemap entries to original disk location (format as URL on Windows)
			devtoolModuleFilenameTemplate: info =>
				path.resolve( info.absoluteResourcePath ).replace( /\\/g, '/' ),

			// config.output can override any of these properties.
			...config.output,
		},

		// Ensure some common plugins are loaded.
		plugins: [
			// Makes some environment variables available to the JS code, for example:
			// if (process.env.NODE_ENV === 'development') { ... }. See `./env.js`.
			new webpack.DefinePlugin( env.stringified ),

			// Emit hot updates.
			new webpack.HotModuleReplacementPlugin(),

			// Output a manifest of the generated assets into the context directory.
			// PHP will ingest this file to load scripts from the dev server.
			manifestFile( config.context ),

			...( config.plugins || [] ),
		],
	};
};

/**
 * Generate a new production Webpack configuration object.
 *
 * The provided configuration object is assumed to specify these keys:
 *
 * - context
 * - entry
 * - output.publicPath
 * - output.fileName
 *
 * @param {Object} config A Webpack configuration object to augment.
 * @return {Object} A Webpack configuration object.
 */

const prodConfig = ( config ) => {
	// Remove dev-mode assets manifest when server quits.
	return {
		mode: 'production',

		devtool: 'hidden-source-map',

		optimization: {
			// TODO: Properly configure Webpack 4's minification. This alone is not enough.
			minimizer: [
				new UglifyJSPlugin({
					sourceMap: true,
					uglifyOptions: {
						comments: false,
						toplevel: true,
						passes: 2,
						max_line_len: 500,
						compress: {
							inline: false
						}
					}
				})
			],
			runtimeChunk: false,
			splitChunks: {
				cacheGroups: {
					default: false,
					commons: {
						test: /[\\/]node_modules[\\/]/,
						name: 'vendor_app',
						chunks: 'all',
						minChunks: 2
					}
				}
			},
		},

		module: {
			strictExportPresence: true,

			rules: [
				loaderRules.js,
				{
					test: /\.s?css$/,
					loader: ExtractTextPlugin.extract( {
						use: loaderRules.scss,
					} ),
				},
				loaderRules.staticAssets,
			],
		},

		// Permit the provided config to override any of the above.
		...config,

		// Set some useful output options.
		output: {
			// Remove /* filename */ comments for generated require()s in the output.
			pathinfo: false,

			// Assume that output paths should be relative to the provided context.
			path: config.context,

			// Point sourcemap entries to original disk location (format as URL on Windows)
			devtoolModuleFilenameTemplate: info =>
				path.resolve( info.absoluteResourcePath ).replace( /\\/g, '/' ),

			// config.output can override any of these properties.
			...config.output,
		},

		// Ensure some common plugins are loaded.
		plugins: [
			// Makes some environment variables available to the JS code, for example:
			// if (process.env.NODE_ENV === 'production') { ... }. See `./env.js`.
			// It is absolutely essential that NODE_ENV was set to production here.
			// Otherwise React will be compiled in the very slow development mode.
			new webpack.DefinePlugin(env.stringified),

			...( config.plugins || [] ),
		],
	};
};

module.exports = {
	loaderRules,
	devConfig,
	prodConfig,
	rootRelative,
};
