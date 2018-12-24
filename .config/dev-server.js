const path = require( 'path' );
const express = require( 'express' );
const webpack = require( 'webpack' );
const devMiddleware = require( 'webpack-dev-middleware' );
const hotMiddleware = require( 'webpack-hot-middleware' );
const merge = require( 'webpack-merge' );
const onExit = require( 'signal-exit' );
const ManifestPlugin = require( 'webpack-manifest-plugin' );

const { deleteFile, devServerPort, devServerUrl, hmrVar } = require( './utils' );

const app = express();
const config = require( './config' )( 'development' );

config.forEach( itemConfig => {
    const cwd = process.cwd();
    const { entry, output, plugins = [] } = itemConfig;
    const { path: outputPath } = output;
    const distPath = path.relative( cwd, outputPath );
    const publicPath = outputPath.replace( cwd, devServerUrl() );

    const manifests = Object.keys( entry ).map( entryPoint => {
        const fileName = `${entryPoint}-manifest.json`;

        onExit( () => deleteFile( `${ outputPath }${ fileName }` ) );

        return new ManifestPlugin( {
            fileName,
            publicPath,
            basePath: `${ distPath }/`,
            writeToFileEmit: true,
        } );
    } );

    const finalConfig = merge( itemConfig, {
        output: {
            publicPath,
        },
        plugins: [
            ...plugins,
            ...manifests,
            new webpack.HotModuleReplacementPlugin(),
        ],
    } );

    const compiler = webpack( finalConfig );

    app
        .use( devMiddleware( compiler, {
            publicPath,
            noInfo: true,
            headers: {
                'Access-Control-Allow-Origin': '*',
            },
        } ) )
        .use( hotMiddleware( compiler, {
            path: `/${ distPath }/${ hmrVar }`,
            heartbeat: 10 * 1000,
        } ) );

} );

app.listen( devServerPort(), () => console.log( `
==> Dev server running on ${ devServerUrl() }.
`) );
