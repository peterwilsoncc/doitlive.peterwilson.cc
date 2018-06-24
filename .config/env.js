/**
 * Expose the environment to the front-end as a JSON object.
 * If complex env variables must be used, replace this with
 * config/env.js from create-react-app.
 */

// Environment variables dictionary
const raw = {
	NODE_ENV: process.env.NODE_ENV || 'development'
};

// Stringify all values so we can feed into Webpack DefinePlugin
const stringified = {
	'process.env': Object.keys(raw).reduce((env, key) => {
		env[key] = JSON.stringify(raw[key]);
		return env;
	}, {}),
};

module.exports = { raw, stringified };
