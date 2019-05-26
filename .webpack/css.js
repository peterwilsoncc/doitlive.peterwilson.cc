const autoprefixer = require( 'autoprefixer' );
const postcssFlexbugsFixes = require( 'postcss-flexbugs-fixes' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

/**
 * Get PostCSS config
 *
 * @param {string} env Environment.
 *
 * @return {Object}
 */
function getCSSConfig( env ) {
	const isProduction = env === 'production';
	const config = {
		module: {
			rules: [ {
				test: /\.s?css$/,
				// NOTE: Order is important.
				use: [
					( ! isProduction && { loader: 'style-loader' } ),
					( isProduction && MiniCssExtractPlugin.loader ),
					{
						loader: 'css-loader',
						options: {
							importLoaders: 1,
							sourceMap: true,
						},
					},
					{
						loader: 'clean-css-loader',
						options: isProduction
							? {
								level: 2,
								inline: [ 'remote' ],
								format: {
									wrapAt: 200,
								},
							}
							: { level: 0 }
					},
					{
						loader: 'postcss-loader',
						options: {
							ident: 'postcss',
							plugins: [
								postcssFlexbugsFixes,
								autoprefixer,
							],
						}
					},
					{
						loader: require.resolve( 'sass-loader' ),
						options: { sourceMap: true },
					},
				].filter( Boolean ),
			} ],
		},
		plugins: [
			( isProduction && new MiniCssExtractPlugin( {
				filename: '[name].css',
			} ) ),
		].filter( Boolean ),
	};

	return config;
}

module.exports = getCSSConfig;
