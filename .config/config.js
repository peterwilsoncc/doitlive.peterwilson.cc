const merge = require( 'webpack-merge' );
const { createEntries } = require( './utils' );
const getSharedConfig = require( './shared' );
const getCSSConfig = require( './css' );

module.exports = env => {
	const isDevelopment = env === 'development';
	const sharedConfig = getSharedConfig( env );
	const cssConfig = getCSSConfig( env );

	return [
		// Theme.
		merge(
			sharedConfig,
			cssConfig,
			createEntries(
				'app',
				'themes/pwcc-003/assets',
				isDevelopment,
			),
		),
	];
};
