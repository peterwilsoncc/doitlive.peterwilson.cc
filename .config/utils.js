const path = require( 'path' );
const { unlinkSync } = require( 'fs' );

const hmrVar = '__webpack_hmr';

/**
 * Get the specified port on which to run the dev server
 */
function devServerPort() {
	return parseInt( process.env.PORT, 10 ) || 8888;
}

function devServerUrl() {
	return `http://localhost:${ devServerPort() }`
}

/**
 * Delete file
 *
 * @param {string} fileName Full path of filename to delete.
 */
function deleteFile( fileName ) {
	try {
		unlinkSync( fileName );
	} catch ( e ) {
		// Silently ignore unlinking errors: so long as the file is gone, that is good.
	}
}

const createHmrEntry = outputPath => [
	'webpack-hot-middleware/client',
	`?path=${ devServerUrl() }/${ outputPath }/${ hmrVar }`,
	'&timeout=20000',
	'&reload=true'
].join( '' );

/**
 * Create entries
 *
 * @param {String|Array}  entries       Entries object.
 * @param {String}        basePath      Base path or the entrypoints, relative to package.json.
 * @param {boolean}       isDevelopment Is this config for development?
 *
 * @return {Object} An object containing `entry`, `output` and `plugins` (when manifest is enabled).
 */
function createEntries( names, basePath, isDevelopment = false ) {
	const context = path.resolve( process.cwd(), basePath );
	const entryPoints = Array.isArray( names ) ? names : [ names ];

	const entry = entryPoints.reduce( ( carry, entryPoint ) => {
		const mainSource = `./src/${ entryPoint }`;
		const sources = isDevelopment
			? [ mainSource, createHmrEntry( `${ basePath }/dist` ) ]
			: mainSource;

		return {
			...carry,
			[ entryPoint ]: sources,
		};
	}, {} );

	return {
		context,
		entry,
		output: {
			path: `${ context }/build/`,
		},
	};
}

module.exports = {
	hmrVar,
	devServerPort,
	devServerUrl,
	deleteFile,
	createEntries,
};
