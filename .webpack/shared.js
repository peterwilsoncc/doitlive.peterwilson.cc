const webpack = require( 'webpack' );
const merge = require( 'webpack-merge' );
const { devServerPort, devServerUrl } = require( './utils' );

/**
 * Get shared config
 *
 * @param {string} env Environment.
 *
 * @return {Object}
 */
function getSharedConfig( env ) {
	const isProduction = env === 'production';

	return {
		mode: env,
		devtool: isProduction
			? 'hidden-source-map'
			: 'cheap-module-eval-source-map',
		output: {
			filename: '[name].js',
			libraryTarget: 'this',
            publicPath: '/',
		},
		resolve: {
			extensions: [ '.js' ],
		},
		module: {
			strictExportPresence: true,
			rules: [ {
				test: /\.jsx?$/,
				loader: 'babel-loader',
				exclude: /(node_modules|bower_components)/,
				options: {
					babelrc: false,
					cacheDirectory: true,
					presets: [
						[ '@babel/preset-env', { modules: false } ],
					],
					plugins: [
						'@babel/plugin-proposal-class-properties',
						'@babel/plugin-proposal-object-rest-spread',
						'@babel/plugin-transform-react-jsx',
						[ '@babel/plugin-transform-runtime', {
							corejs: 2,
							helpers: true,
							useESModules: false,
						} ],
					].filter( Boolean ),
				},
			}, {
				test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|eot|ttf)$/,
				loader: 'url-loader',
				options: {
					name: 'static/[name].[sha1:hash:base36:8].[ext]',
					publicPath: isProduction ? './' : undefined,
					limit: 100,
				},
			}, {
				// Exclude all extensions that have their own loader.
				exclude: [
					/\.p?css$/, /\.sass$/, /\.scss$/,
					/\.html$/, /\.json$/, /\.jsx?$/,
					/\.png$/, /\.jpe?g$/, /\.gif$/, /\.svg$/,
					/\.woff$/, /\.woff2$/, /\.eot$/, /\.ttf$/,
				],
				loader: 'file-loader',
			} ],
		},
		performance: {
			assetFilter: assetFilename => !( /\.map$/.test( assetFilename ) ),
			...( isProduction ? {} : {
				maxAssetSize: 1000000, // 1 mB.
				maxEntrypointSize: 1000000,
			} ),
		},
	};
}

module.exports = getSharedConfig;
